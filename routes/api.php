<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\StatsController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//3.a. /register endpoint that receives and validates the following:
Route::post("register",[AuthController::class,"register"]);
//3.b. /login endpoint.
Route::post("login",[AuthController::class,"login"]);

//3.f. Make an endpoint that verifies the code sent to the user.
Route::post('/verify', [AuthController::class, 'verify']);

Route::get('/stats', [StatsController::class, 'index']);
Route::get('/test', [StatsController::class, 'test']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    //4- Create tags API resource.
    Route::resource('tags',TagController::class)->only(["index","store","update","destroy"]);
    //5- Create posts API resource.
    Route::resource('posts',PostController::class);
    Route::get("deletedPosts",[PostController::class,"softDeletedPosts"]);
    Route::post("posts/restore/{post}",[PostController::class,"restore"]);
    Route::post("posts/pin/{post}",[PostController::class,"pin"]);

    //    Route::resources([
//        'tags' => TagController::class,
//        'posts' => PostController::class,
//    ]);
});
