<?php

declare(strict_types=1);

namespace App\Jobs\PublicWebsite;

use App\Enums\Queue;
use App\Services\PublicWebsite\PublicSitemapCheckException;
use App\Services\PublicWebsite\PublicSitemapCheckRetryException;
use App\Services\PublicWebsite\PublicWebsiteCheckService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Psr\Log\LoggerInterface;

class PublicWebsiteCheckJob implements ShouldQueue
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
        PublicWebsiteCheckService $publicWebsiteCheckService,
    ): void {
        try {
            $publicWebsiteCheckService->check();
        } catch (PublicSitemapCheckException $publicSitemapCheckException) {
            $logger->notice('Public website publication checks failed', ['message' => $publicSitemapCheckException->getMessage()]);
        } catch (PublicSitemapCheckRetryException $publicSitemapCheckRetryException) {
            $logger->info('Public website publication checks retry', ['message' => $publicSitemapCheckRetryException->getMessage()]);
            $this->release(60);
        }

        $logger->info('Public website publication checks done');
    }
}
