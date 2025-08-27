<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Events\StaticWebsite\BuildEvent;
use Illuminate\Console\Command;

class StaticWebsiteRefresh extends Command
{
    protected $signature = 'static-website:refresh';
    protected $description = 'Regenerate & publish the static-website';

    public function handle(): int
    {
        BuildEvent::dispatch();

        $this->output->success('Static website refresh jobs dispatched, see worker logs for details');

        return self::SUCCESS;
    }
}
