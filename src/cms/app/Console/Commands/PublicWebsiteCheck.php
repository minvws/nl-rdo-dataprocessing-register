<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\PublicWebsite\PublicWebsiteCheckForcedJob;
use App\Jobs\PublicWebsite\PublicWebsiteCheckJob;
use Illuminate\Console\Command;
use Psr\Log\LoggerInterface;

class PublicWebsiteCheck extends Command
{
    protected $description = 'Check content of public-website & mark processing-records publications';
    protected $signature = 'public-website:check {--F|force}';

    public function handle(LoggerInterface $logger): int
    {
        $logger->info('Public website check command start');

        $force = $this->option('force');
        $force ? PublicWebsiteCheckForcedJob::dispatch() : PublicWebsiteCheckJob::dispatch();

        $this->output->success('Public website check job dispatched, see worker logs for details');

        return self::SUCCESS;
    }
}
