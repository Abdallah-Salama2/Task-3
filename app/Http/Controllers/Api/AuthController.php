<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        // Validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255|unique:users,phone',
            'password' => 'required|string|min:8'
        ]);
        //d. Generate random 6-digits verification code for every user.
        $verificationCode=rand(100000, 999999);
        // Create the user
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'verification_code'=>rand(100000, 999999),
        ]);

//        e. Send the code for every user (Just log it).
        Log::info('Verification code for ' . $user->phone_number . ': ' . $verificationCode);

        //Create token
        $token = $user->createToken('API Token')->plainTextToken;

        //3.c. Both of the previous endpoint should return the user data with access token.
        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);


    }

    public function login(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();
//        dd($user->isVerified());
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials']);
        }

        if(!$user->isVerified()){
            return response()->json(['message' => 'Account not verified']);
        }
        $token = $user->createToken('API Token')->plainTextToken;

        //3.c. Both of the previous endpoint should return the user data with access token.
        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);

    }

    //g. Only verified accounts can login to the system.
    public function verify(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'verification_code' => 'required|string',
        ]);
        $user = User::where('phone', $request->phone)
            ->first();
        if($request->verification_code == $user->verification_code){
            $user->verified_at=now();
            $user->save();
        }else{
            return response()->json(['message' => 'Invalid verification code'], 401);
        }
//        dd($request,$user);

        if (!$user) {
            return response()->json(['message' => 'Invalid verification code'], 401);
        }

        $user->verification_code = null;
        $user->save();

        return response()->json(['message' => 'Account verified successfully']);
    }
    public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
