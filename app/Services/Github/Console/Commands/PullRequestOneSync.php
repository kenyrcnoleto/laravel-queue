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
            new \App\Services\Github\Jobs\FailedJob(),
            ])
            ->then(function() {
                info('All jobs completed successfully.');
            })
            ->name('github repository sync: ' . $repositoryFullName)
            ->allowFailures()
            ->dispatch();
            // \App\Jobs\PullRequestsSync::dispatch($this->argument('repositoryFullName'));
            // \App\Services\Github\Jobs\PullRequestOneSync::dispatch($this->argument('repositoryFullName'));
    }
}
