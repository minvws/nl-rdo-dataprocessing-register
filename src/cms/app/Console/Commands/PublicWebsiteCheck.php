<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\PublicWebsite\PublicWebsiteCheckJob;
use Illuminate\Console\Command;

class PublicWebsiteCheck extends Command
{
    protected $description = 'Check content of public-website & mark processing-records publications';
    protected $signature = 'public-website:check';

    public function handle(): int
    {
        PublicWebsiteCheckJob::dispatch();

        $this->output->success('Public website check job dispatched, see worker logs for details');

        return self::SUCCESS;
    }
}
