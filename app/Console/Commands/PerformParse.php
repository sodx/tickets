<?php

namespace App\Console\Commands;

use App\Http\Controllers\TicketMasterController;
use App\Models\User;
use Cache;
use Illuminate\Console\Command;

class PerformParse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'perform:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a marketing email to a user';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $ticketMasterController = new TicketMasterController();
        $ticketMasterController->checkEventsStatus();
        \Artisan::call('cache:clear');
    }
}
