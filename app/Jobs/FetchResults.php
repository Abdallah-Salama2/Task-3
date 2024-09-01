<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchResults implements ShouldQueue
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
     * 7- Create a job that runs every six hours and makes HTTP Request to this end endpoint and log only the results
     * object in the response. https://randomuser.me/api/
     */
    public function handle(): void
    {
        //
        $response = Http::withoutVerifying()->get('https://randomuser.me/api/');
        if ($response->successful()) {
            $results = $response->json('results');

            Log::info('Results:', $results);
        } else {
            Log::error('Failed to fetch data .');
        }


    }
}
