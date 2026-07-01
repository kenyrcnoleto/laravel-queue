<?php

namespace App\Jobs;

use App\Models\PullRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class PullRequestsSync implements ShouldQueue
{
    use Queueable;



    /**
     * Execute the job.
     */
    public function handle(): void
    {
        dump('PullRequestsSync job executed');
        $pullRequests = Http::get('https://api.github.com/repos/laravel/laravel/pulls');

       foreach ($pullRequests->json() as $pullRequest) {
            PullRequest::create(
                [
                    'api_id' => $pullRequest['id'],
                    'api_number' => $pullRequest['number'],
                    'state' => $pullRequest['state'],
                    'title' => $pullRequest['title'],
                    'api_created_at' => Carbon::parse($pullRequest['created_at'])->format('Y-m-d H:i:s'),
                    'api_updated_at' => Carbon::parse($pullRequest['updated_at'])->format('Y-m-d H:i:s'),
                    'api_closed_at' => Carbon::parse($pullRequest['closed_at'])->format('Y-m-d H:i:s'),
                    'api_merged_at' => Carbon::parse($pullRequest['merged_at'])->format('Y-m-d H:i:s'),
                ]
            );
       }
    }
}
