<?php

declare(strict_types=1);

namespace App\Jobs\PublicWebsite;

use App\Repositories\AdminLogRepository;
use App\Services\PublicWebsite\PublicWebsiteGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

use function sprintf;

class HugoWebsiteGeneratorJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function handle(
        AdminLogRepository $adminLogRepository,
        PublicWebsiteGenerator $websiteGenerator,
    ): void {
        $adminLogRepository->timedLog(
            static function () use ($websiteGenerator): void {
                $websiteGenerator->generate();
            },
            sprintf('Processed job: "%s"', self::class),
        );
    }
}
