<?php

declare(strict_types=1);

namespace App\Services\PublicWebsite;

use Psr\Log\LoggerInterface;

readonly class FakePublicWebsiteGenerator implements PublicWebsiteGenerator
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function generate(): void
    {
        $this->logger->debug('Fake public website generated');
    }
}
