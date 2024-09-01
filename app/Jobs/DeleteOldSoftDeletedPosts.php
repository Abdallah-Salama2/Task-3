<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class DeleteOldSoftDeletedPosts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     * 6- Create a Job that runs daily and force-deletes all softly-deleted posts for more than 30 days.
     */
    public function handle(): void
    {
        //
        $thresholdDate = Carbon::now()->subDays(30);
        $oldPosts = Post::onlyTrashed()->where('deleted_at', '<', $thresholdDate)->get();

        // Force delete the old posts
        foreach ($oldPosts as $post) {
            $post->forceDelete();
        }
    }
}
