<?php

namespace App\Services\Github\Commands;

use Illuminate\Console\Command;

class PullRequestOneSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:pull-requests-one-sync {repositoryFullName}';

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
        \App\Jobs\PullRequestsSync::dispatch($this->argument('repositoryFullName'));
    }
}
