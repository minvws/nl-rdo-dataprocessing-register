<?php

declare(strict_types=1);

namespace App\Services\StaticWebsite;

use Psr\Log\LoggerInterface;

readonly class FakeStaticWebsiteGenerator implements StaticWebsiteGenerator
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function generate(): void
    {
        $this->logger->debug('Fake static website generated');
    }
}
