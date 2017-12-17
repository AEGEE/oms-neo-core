<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckHealth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'service:health';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks the health of the service using the CLI';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!\DatabaseSeeder::isSeeded()) {
            $this->error("Service is unhealthy");
            exit(1);
        } else {
            $this->info("Service is healthy");
            exit(0);
        }
    }
}
