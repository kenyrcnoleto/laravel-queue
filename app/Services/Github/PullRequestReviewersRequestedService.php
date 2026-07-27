<?php

namespace App\Services\Github;

use Illuminate\Support\Facades\Http;

class PullRequestReviewersRequestedService
{
   public function getAll(string $repositoryFullName, int $pullRequestNumber): array
   {
       $url = 'repos/' . $repositoryFullName . '/pulls/' . $pullRequestNumber . '/requested_reviewers';

    //    $pullRequestResponse = Http::withToken(config('services.github.personal_access_token'))
    //        ->get($url);

    $response = (new Client())->http()->get($url);

       return $response->json();
   }


}
