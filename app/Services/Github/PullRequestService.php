<?php

namespace App\Services\Github;

use Illuminate\Support\Facades\Http;

class PullRequestService
{
   public function getPullRequests(string $repositoryFullName, int $page = 1): array
   {
         $queryString = http_build_query(
            [
                'state' => 'all',
                'page' => $page,
            ],
            arg_separator: '&', encoding_type: PHP_QUERY_RFC3986
        );

       $url = 'repos/' . $repositoryFullName . '/pulls?' . $queryString;

    //    $pullRequestResponse = Http::withToken(config('services.github.personal_access_token'))
    //        ->get($url);

    $response = (new Client())->http()->get($url);

       return $response->json();
   }

   public function getPullRequest(string $repositoryFullName, int $number): array
   {
       $url = 'repos/' . $repositoryFullName . '/pulls/' . $number;

    //    $pullRequestResponse = Http::withToken(config('services.github.personal_access_token'))
    //        ->get($url);

    $response = (new Client())->http()->get($url);

       return $response->json();

   }


}
