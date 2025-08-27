<?php

declare(strict_types=1);

namespace App\Listeners\StaticWebsite;

use App\Jobs\StaticWebsite\AfterBuildHookJob;
use Psr\Log\LoggerInterface;

class AfterBuildHandler
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly ?string $afterBuildHook,
    ) {
    }

    public function handle(): void
    {
        $this->logger->debug('After build handler triggered', [
            'afterBuildHook' => $this->afterBuildHook,
        ]);

        if ($this->afterBuildHook === null) {
            return;
        }

        AfterBuildHookJob::dispatch($this->afterBuildHook);
    }
}
