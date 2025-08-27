<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Events\PublicWebsite\BuildEvent;
use Illuminate\Console\Command;

class PublicWebsiteRefresh extends Command
{
    protected $signature = 'public-website:refresh';
    protected $description = 'Regenerate & publish the public-website';

    public function handle(): int
    {
        BuildEvent::dispatch();

        $this->output->success('Public website refresh jobs dispatched, see worker logs for details');

        return self::SUCCESS;
    }
}
