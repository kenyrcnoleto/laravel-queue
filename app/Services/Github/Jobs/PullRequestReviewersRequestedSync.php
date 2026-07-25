<?php

namespace App\Services\Github\Jobs;

use App\Models\PullRequest;
use App\Services\Github\PullRequestService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class PullRequestSync implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */

    //Ir para api pegar os dados do PR
    //Salvar o PR no banco de dados

    public function __construct(public string $repositoryFullName, public int $number)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $pullRequest = (new PullRequestService())->getPullRequest($this->repositoryFullName, $this->number);

        PullRequest::create(
            [
                'api_id'        => $pullRequest['id'],
                'api_number'    => $pullRequest['number'],
                'state'         => $pullRequest['state'],
                'title'         => $pullRequest['title'],
                'commits_total' => $pullRequest['commits'],
                'api_created_at'=> Carbon::parse($pullRequest['created_at'])->format('Y-m-d H:i:s'),
                'api_updated_at'=> Carbon::parse($pullRequest['updated_at'])->format('Y-m-d H:i:s'),
                'api_closed_at' => Carbon::parse($pullRequest['closed_at'])->format('Y-m-d H:i:s'),
                'api_merged_at' => Carbon::parse($pullRequest['merged_at'])->format('Y-m-d H:i:s'),
            ]
        );
    }
}
