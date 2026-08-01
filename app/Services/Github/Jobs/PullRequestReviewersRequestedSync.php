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

class PullRequestReviewersRequestedSync implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */

    //Ir para api pegar os dados do PR
    //Salvar o PR no banco de dados

    public function __construct(public string $repositoryFullName, public int $pullRequestNumber)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //chamara aclasse de serviço para pegar os dados do PR na API do Github
        $response =(new PullRequestReviewersRequestedService())->getAll($this->repositoryFullName, $this->pullRequestNumber);

        //Pegar os dados dos colaboradores que foram solicitados para revisar o PR
            $collaborators = $response['users'];

            foreach ($collaborators as $collaborator) {
                $collaborator = Collaborator::updateOrCreate(
                    [
                        'api_id' => $collaborator['id'],
                    ],
                    [
                        'login' => $collaborator['login'],
                    ]
                );
                //Pegar o PR do banco de dados
            $pr = PullRequest::where('api_number', $this->pullRequestNumber)->first();

            $collaborator->pullRequests()->attach($pr->id);
            //O que seria esses attach? seria para salvar no banco de dados a relação entre o PR e o colaborador que foi solicitado a revisar o PR
            }

    }
}
