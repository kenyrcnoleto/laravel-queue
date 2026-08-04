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

        Bus::batch([
            new  \App\Services\Github\Jobs\PullRequestOneSync($this->argument('repositoryFullName')),
            ])
            ->then(function() {
                info('All jobs completed successfully.');
            })
            ->dispatch();
            // \App\Jobs\PullRequestsSync::dispatch($this->argument('repositoryFullName'));
            // \App\Services\Github\Jobs\PullRequestOneSync::dispatch($this->argument('repositoryFullName'));
    }
}
