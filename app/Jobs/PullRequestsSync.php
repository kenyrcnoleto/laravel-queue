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



    public function __construct(public ?int $page = 1)
    {
        //
    }



    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // dump('PullRequestsSync job executed');
        // $this->sync();

        $url = 'https://api.github.com/repos/laravel/laravel/pulls?state=all&page=' . $this->page;

        dump('PullRequestsSync job executed', $url);

        $pullRequestsResponse = Http::withToken(config('services.github.personal_access_token'))
                                ->get($url);

        $pullRequests = $pullRequestsResponse->json();

        // dd('ok',$pullRequests);

        if(empty($pullRequests) || !is_array($pullRequests)) {
            return;
        }


       foreach ($pullRequests as $pullRequest) {
            PullRequestStore::dispatch($pullRequest);
       }

       PullRequestsSync::dispatch($this->page + 1);

    }


    /*public function sync(int $page = 1): void
    {
        $pullRequestsResponse = Http::get('https://api.github.com/repos/laravel/laravel/pulls?state=all&page=' . $page);

        $pullRequests = $pullRequestsResponse->json();

        if(empty($pullRequests) || !is_array($pullRequests)) {
            return;
        }

        foreach ($pullRequests as $pullRequest) {

            dd($pullRequest);
        }

        foreach ($pullRequests as $pullRequest) {
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

       $this->sync($page + 1);

    }*/
}
