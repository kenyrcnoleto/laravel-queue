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



    public function __construct(public string $repositoryFullName, public ?int $page = 1)
    {
        //
    }


    //Economizar chamadas ao banco de dados, evitando a criação de registros duplicados. vs job responsabilidade unica
    //ou economiza memória, evitando a criação de registros duplicados em memória.
    //economiza processamento, evitando a criação de registros duplicados em memória e no banco de dados.

    //  Fazer a pergunta: E se esse job falhar?
    // o job precisa ter responsabilidade unica, e não depender de outros jobs para funcionar corretamente.
    //tem que processar o mais rápido possível, e não depender de outros jobs para funcionar corretamente.
    //deve ser sucetível a falhas, e não depender de outros jobs para funcionar corretamente.

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // dump('PullRequestsSync job executed');
        // $this->sync();

        //Obter a lista de pull requests do repositório laravel/laravel usando a API do GitHub.
        $url = 'https://api.github.com/repos/' . $this->repositoryFullName . '/pulls?state=all&page=' . $this->page;

        dump('PullRequestsSync job executed', $url);
        // dd('deu certo');

        $pullRequestsResponse = Http::withToken(config('services.github.personal_access_token'))
                                ->get($url);

        $pullRequests = $pullRequestsResponse->json();

        // dd('ok',$pullRequests);

        // Verificar se a resposta da API é válida e contém pull requests, caso contrário, retornar sem fazer nada.
        if(empty($pullRequests) || !is_array($pullRequests)) {
            return;
        }


       foreach ($pullRequests as $pullRequest) {
            //Salvar cada pull request em um job separado, para evitar sobrecarga de memória e processamento.
            PullRequestStore::dispatch($this->repositoryFullName, $pullRequest['number']);
       }

       $nextPage = $this->page + 1;

       // Chamar o próximo job para a próxima página de pull requests.
       PullRequestsSync::dispatch($this->repositoryFullName, $nextPage);

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
