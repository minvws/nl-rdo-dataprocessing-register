<?php

declare(strict_types=1);

namespace App\Listeners\StaticWebsite;

use App\Jobs\StaticWebsite\ContentGeneratorJob;
use App\Jobs\StaticWebsite\HugoWebsiteGeneratorJob;
use Illuminate\Support\Facades\Bus;
use Psr\Log\LoggerInterface;

readonly class BuildHandler
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function handle(): void
    {
        $this->logger->debug('StaticWebsite build handler triggered');

        $jobs = [
            new ContentGeneratorJob(),
            new HugoWebsiteGeneratorJob(),
        ];

        Bus::chain($jobs)->dispatch();
    }
}
