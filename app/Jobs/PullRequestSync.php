<?php

namespace App\Jobs;

use App\Models\PullRequest;
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
        $url = 'https://api.github.com/repos/' . $this->repositoryFullName . '/pulls/' . $this->number;

        dump('PullRequestsSync job executed', $url);
        // dd('deu certo');

        $pullRequestsResponse = Http::withToken(config('services.github.personal_access_token'))
            ->get($url);

        $pullRequest = $pullRequestsResponse->json();
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
