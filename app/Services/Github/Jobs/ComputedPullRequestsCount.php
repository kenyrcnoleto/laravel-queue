<?php

namespace App\Services\Github\Jobs;

use App\Models\PullRequest;
use App\Models\Total;
use App\Services\Github\PullRequestService;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\SkipIfBatchCancelled;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class ComputedPullRequestsCount implements ShouldQueue
{
    use Queueable;
    use Batchable;



    public function __construct()
    {
        //
    }

    public function middleware(): array
    {
        return [
            new SkipIfBatchCancelled
        ];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Total::updateOrCreate(
            [],
            [
                'pull_requests_count' => PullRequest::query()->count(),
            ]
        );

    }

}
