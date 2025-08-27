<?php

declare(strict_types=1);

namespace App\Listeners\PublicWebsite;

use App\Jobs\PublicWebsite\ContentGeneratorJob;
use App\Jobs\PublicWebsite\HugoWebsiteGeneratorJob;
use App\Jobs\PublicWebsite\PublicWebsiteCheckJob;
use App\Services\BuildContextService;
use Illuminate\Bus\Dispatcher;
use Psr\Log\LoggerInterface;

readonly class BuildHandler
{
    public function __construct(
        private BuildContextService $buildContextService,
        private Dispatcher $bus,
        private LoggerInterface $logger,
    ) {
    }

    public function handle(): void
    {
        $this->logger->debug('PublicWebsite build handler triggered');

        if ($this->buildContextService->isBuildDisabled()) {
            $this->logger->debug('PublicWebsite build handler is disabled');
            return;
        }

        $jobs = [
            new ContentGeneratorJob(),
            new HugoWebsiteGeneratorJob(),
            new PublicWebsiteCheckJob(),
        ];

        $this->bus->chain($jobs)->dispatch();
    }
}
