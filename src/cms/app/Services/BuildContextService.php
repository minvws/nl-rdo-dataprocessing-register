<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Log\Context\Repository as ContextRepository;
use Psr\Log\LoggerInterface;
use Webmozart\Assert\Assert;

class BuildContextService
{
    private const CONTEXT_KEY_BUILD_ENABLED = 'build_enabled';

    public function __construct(
        private readonly ContextRepository $context,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function disableBuild(): void
    {
        $this->setState(false);
    }

    public function enableBuild(): void
    {
        $this->setState(true);
    }

    public function isBuildDisabled(): bool
    {
        return !$this->getState();
    }

    public function isBuildEnabled(): bool
    {
        return $this->getState();
    }

    private function getState(): bool
    {
        $state = $this->context->getHidden(self::CONTEXT_KEY_BUILD_ENABLED, true);
        Assert::boolean($state);

        return $state;
    }

    private function setState(bool $state): void
    {
        $this->context->addHidden(self::CONTEXT_KEY_BUILD_ENABLED, $state);

        $this->logger->info('Build state set', ['state' => $state]);
    }
}
