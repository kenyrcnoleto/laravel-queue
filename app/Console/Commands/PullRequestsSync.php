<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

class PullRequestsSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:pull-requests-sync {repositoryFullName}';

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
            new \App\Jobs\PullRequestsSync($this->argument('repositoryFullName')),
        ])->dispatch();
    }
}
