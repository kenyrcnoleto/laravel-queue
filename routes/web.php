<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    $pullRequests = Http::get('https://api.github.com/repos/laravel/laravel/pulls?state=all');

    foreach($pullRequests->json() as $pullRequest) {
        \App\Models\PullRequest::create(
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

    dd($pullRequests->json());
});
