<?php

namespace App\Services\Github\Jobs;

use App\Models\PullRequest;
use App\Services\Github\PullRequestService;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class FailedJob implements ShouldQueue
{
    use Queueable;
    use Batchable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //  throw new \Exception('Method not implemented.');
    }


}
