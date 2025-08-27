<?php

declare(strict_types=1);

namespace App\Jobs\PublicWebsite;

use App\Enums\Queue;
use App\Repositories\AdminLogRepository;
use App\Services\PublicWebsite\ContentGenerator;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

use function sprintf;

class ContentGeneratorJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function __construct()
    {
        $this->onQueue(Queue::LOW);
    }

    /**
     * @throws Exception
     */
    public function handle(
        AdminLogRepository $adminLogRepository,
        ContentGenerator $contentGenerator,
    ): void {
        $adminLogRepository->timedLog(
            static function () use ($contentGenerator): void {
                $contentGenerator->generate();
            },
            sprintf('Processed job: "%s"', self::class),
        );
    }
}
