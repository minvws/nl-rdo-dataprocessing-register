<?php

declare(strict_types=1);

namespace App\Listeners\PublicWebsite;

use App\Jobs\PublicWebsite\AfterBuildHookJob;
use App\Jobs\PublicWebsite\PublicWebsiteCheckJob;
use Carbon\CarbonImmutable;
use Psr\Log\LoggerInterface;

class AfterBuildHandler
{
    /**
     * @param array<int> $planCheckJobDelays
     */
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly ?string $afterBuildHook,
        private readonly array $planCheckJobDelays,
    ) {
    }

    public function handle(): void
    {
        $this->logger->debug('After build handler triggered', [
            'afterBuildHook' => $this->afterBuildHook,
        ]);

        // plan website checks with delays
        foreach ($this->planCheckJobDelays as $delay) {
            PublicWebsiteCheckJob::dispatch()
                ->delay(CarbonImmutable::now()->addMinutes($delay)->floorMinute());
        }
        $this->logger->debug('planned website checks with delays', ['delays' => $this->planCheckJobDelays]);

        if ($this->afterBuildHook === null) {
            return;
        }

        AfterBuildHookJob::dispatch($this->afterBuildHook);
    }
}
