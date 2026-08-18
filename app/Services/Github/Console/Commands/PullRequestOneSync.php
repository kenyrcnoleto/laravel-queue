<?php

namespace App\Services\Github\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

class PullRequestOneSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'github:pull-requests-one-sync {repositoryFullName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

    $repositoryFullName = $this->argument('repositoryFullName');
        Bus::batch([
            new  \App\Services\Github\Jobs\PullRequestOneSync($repositoryFullName),
            // new \App\Services\Github\Jobs\FailedJob(),

            //Sync todos os dados do repositório
            //Compute data
            //Computed tables
            //total users = 10
            // Saber quantos PRs tem ao todo na aplicação

            // Cout dos Prs - muito lenta e complexa, então não é possível fazer isso em tempo real, então vamos criar uma tabela para armazenar o total de PRs e atualizar essa tabela a cada sync
            ])
            ->then(function() {

                Bus::batch([
                    new \App\Services\Github\Jobs\ComputedPullRequestsCount(),

                ])
                 ->then(function() {
                     info('All computed data jobs completed successfully.');
                })
                ->name('github computed data sync')
                ->allowFailures()
                ->dispatch();

                // new ComputedPullRequestsCount::dispatch();
                // new ComputedUsersCount::dispatch();
                // new ComputedCommitsCount::dispatch();
                // new ComputedCollaboratorsCount::dispatch();

                // info('All jobs completed successfully.');
            })
            ->name('github repository sync: ' . $repositoryFullName)
            ->allowFailures()
            ->dispatch();
            // \App\Jobs\PullRequestsSync::dispatch($this->argument('repositoryFullName'));
            // \App\Services\Github\Jobs\PullRequestOneSync::dispatch($this->argument('repositoryFullName'));
    }
}
