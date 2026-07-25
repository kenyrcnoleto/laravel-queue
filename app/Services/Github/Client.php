<?php

namespace App\Services\Github;

use Illuminate\Http\Client\PendingRequest;

class Client
{
    private string $baseUrl = 'https://api.github.com/';

    public function http(): PendingRequest
    {
        return \Illuminate\Support\Facades\Http::withOptions([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Accept' => 'application/vnd.github+json',
                'X-GitHub-Api-Version' => '2022-11-28',
            ],
        ])->withToken(config('services.github.personal_access_token'));



         $url = 'https://api.github.com/repos/' . $repositoryFullName . '/pulls/' . $pullRequestNumber . '/requested_reviewers';

          $pullRequestResponse = Http::withToken(config('services.github.personal_access_token'))
           ->get($url);

        return $pullRequestResponse->json();
    }

    public function pullRequestService(): PullRequestService
    {
        return new PullRequestService();
    }

    public function pullRequestReviewersRequestedService(): PullRequestReviewersRequestedService
    {
        return new PullRequestReviewersRequestedService();
    }
}
