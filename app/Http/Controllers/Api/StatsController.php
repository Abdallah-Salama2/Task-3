<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StatsController extends Controller
{
    /**
     *a. That endpoint should return the following:
     * i. Number of all users.
     * ii. Number of all posts.
     * iii. Number of users with 0 posts.
     *
     * b. The results should be cached and update with every update to the related models (User and Post).
     */
    public function index()
    {
//        dd(Cache::get("stats"));
        $stats = Cache::remember('stats', 60*60, function () {
            return [
                'total_users' => User::count(),
                'total_posts' => Post::count(),
                'users_with_no_posts' => User::doesntHave('posts')->count(),
            ];
        });

        return response()->json($stats);
    }

    public function test(){
        $response = Http::withoutVerifying()->get('https://randomuser.me/api/');
        if ($response->successful()) {
            $results = $response->json('results');

            // Log the 'results' object
//            Log::info('Random User Results:', $results);
        } else {
            }

        return $results;
    }


}
