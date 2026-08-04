<?php

namespace App\Services\Github\Jobs;

use App\Models\PullRequest;
use App\Services\Github\PullRequestService;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class PullRequestOneSync implements ShouldQueue
{
    use Queueable;
    use Batchable;



    public function __construct(public string $repositoryFullName, public ?int $page = 1)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $pullRequests = (new PullRequestService())->getPullRequests($this->repositoryFullName, $this->page);


        if(empty($pullRequests) || !is_array($pullRequests)) {
            return;
        }


       foreach ($pullRequests as $pullRequest) {
            //Salvar cada pull request em um job separado, para evitar sobrecarga de memória e processamento.
            $this->batch()->add([new PullRequestSync($this->repositoryFullName, $pullRequest['number'])]);
            // PullRequestSync::dispatch($this->repositoryFullName, $pullRequest['number']);
       }

       $nextPage = $this->page + 1;

       // Chamar o próximo job para a próxima página de pull requests.
       PullRequestOneSync::dispatch($this->repositoryFullName, $nextPage);

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
