<?php

declare(strict_types=1);

namespace App\Jobs\PublicWebsite;

use App\Enums\Queue;
use App\Facades\AdminLog;
use App\Services\PublicWebsite\ContentGenerator;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;

use function sprintf;

class PublicContentDeleteJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function __construct()
    {
        $this->onQueue(Queue::DEFAULT);
    }

    /**
     * @throws Exception
     */
    public function handle(ContentGenerator $contentGenerator): void
    {
        AdminLog::timedLog(
            static function () use ($contentGenerator): void {
                $contentGenerator->delete();
            },
            sprintf('Processed job: "%s"', Str::of(self::class)->afterLast('\\')),
        );
    }
}
