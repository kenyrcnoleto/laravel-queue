<?php

namespace App\Services\Github\Jobs;

use App\Models\Collaborator;
use App\Models\PullRequest;
use App\Services\Github\PullRequestReviewersRequestedService;
use App\Services\Github\PullRequestService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Queue\Middleware\SkipIfBatchCancelled;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class PullRequestReviewersRequestedSync implements ShouldQueue
{
    use Queueable;
    use Batchable;

    /**
     * Create a new job instance.
     */

    //Ir para api pegar os dados do PR
    //Salvar o PR no banco de dados

    public function __construct(public string $repositoryFullName, public PullRequest $pullRequest)
    {
        //
    }

    public function middleware(): array
    {
        return [
            new SkipIfBatchCancelled
        ];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {


        //chamara aclasse de serviço para pegar os dados do PR na API do Github
        $response =(new PullRequestReviewersRequestedService())->getAll($this->repositoryFullName, $this->pullRequest->api_number);

        //Pegar os dados dos colaboradores que foram solicitados para revisar o PR
            $collaborators = $response['users'];

            $jobs = [];

            foreach ($collaborators as $collaborator) {
                $jobs[] = new PullRequestReviewerRequestedSync($collaborator, $this->pullRequest);
            }

            $this->batch()->add($jobs);

    }
}
