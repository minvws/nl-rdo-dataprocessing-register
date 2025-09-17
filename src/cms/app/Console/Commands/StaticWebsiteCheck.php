<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\StaticWebsite\StaticWebsiteCheckForcedJob;
use App\Jobs\StaticWebsite\StaticWebsiteCheckJob;
use Illuminate\Console\Command;
use Psr\Log\LoggerInterface;

class StaticWebsiteCheck extends Command
{
    protected $description = 'Check content of static-website & mark processing-records publications';
    protected $signature = 'static-website:check {--F|force}';

    public function handle(LoggerInterface $logger): int
    {
        $logger->info('Static website check command start');

        $force = $this->option('force');
        $force ? StaticWebsiteCheckForcedJob::dispatch() : StaticWebsiteCheckJob::dispatch();

        $this->output->success('Static website check job dispatched, see worker logs for details');

        return self::SUCCESS;
    }
}
