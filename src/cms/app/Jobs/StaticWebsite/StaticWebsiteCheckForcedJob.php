<?php

declare(strict_types=1);

namespace App\Jobs\StaticWebsite;

use App\Enums\Queue;
use App\Services\StaticWebsite\StaticSitemapCheckJsonException;
use App\Services\StaticWebsite\StaticSitemapCheckRequestException;
use App\Services\StaticWebsite\StaticWebsiteCheckService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Psr\Log\LoggerInterface;

class StaticWebsiteCheckForcedJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function __construct()
    {
        $this->onQueue(Queue::LOW);
    }

    public function handle(
        LoggerInterface $logger,
        StaticWebsiteCheckService $staticWebsiteCheckService,
    ): void {
        $logger->info('Static website publication forced checks starting');

        try {
            $staticWebsiteCheckService->checkForced();
        } catch (StaticSitemapCheckJsonException $staticSitemapCheckJsonException) {
            $logger->notice(
                'Static website publication forced checks failed',
                ['message' => $staticSitemapCheckJsonException->getMessage()],
            );
        } catch (StaticSitemapCheckRequestException $staticSitemapCheckRequestException) {
            $logger->info(
                'Static website publication forced checks retry',
                ['message' => $staticSitemapCheckRequestException->getMessage()],
            );
            $this->release(60);
        }

        $logger->info('Static website publication forced checks done');
    }
}
