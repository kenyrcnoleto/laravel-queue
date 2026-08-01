<?php

namespace App\Services\Github\Jobs;

use App\Models\Collaborator;
use App\Models\PullRequest;
use App\Services\Github\PullRequestReviewersRequestedService;
use App\Services\Github\PullRequestService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class PullRequestReviewerRequestedSync implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */


    public function __construct(public array $collaboratorRaw, public PullRequest $pullRequest)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $collaborator = Collaborator::updateOrCreate(
            [
                'api_id' => $this->collaboratorRaw['id'],
            ],
            [
                'login' => $this->collaboratorRaw['login'],
            ]
        );
        //Pegar o PR do banco de dados
    // $pr = PullRequest::where('api_number', $this->pullRequest->api_number)->first();

    $collaborator->pullRequests()->attach($this->pullRequest->id);
    //O que seria esses attach? seria para salvar no banco de dados a relação entre o PR e o colaborador que foi solicitado a revisar o PR

    }
}
