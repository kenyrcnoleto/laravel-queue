<?php

namespace App\Services\Github;

use Illuminate\Support\Facades\Http;

class PullRequestService
{
   public function getPullRequests(string $repositoryFullName, int $page = 1): array
   {
       $url = 'https://api.github.com/repos/' . $repositoryFullName . '/pulls?state=all&page=' . $page;

       $pullRequestResponse = Http::withToken(config('services.github.personal_access_token'))
           ->get($url);

       return $pullRequestResponse->json();
   }

   public function getPullRequest(string $repositoryFullName, int $number): array
   {
       $url = 'https://api.github.com/repos/' . $repositoryFullName . '/pulls/' . $number;

       $pullRequestResponse = Http::withToken(config('services.github.personal_access_token'))
           ->get($url);

       return $pullRequestResponse->json();

   }


}
