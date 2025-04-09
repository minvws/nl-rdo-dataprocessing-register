<?php

declare(strict_types=1);

namespace App\Jobs\PublicWebsite;

use App\Facades\AdminLog;
use App\Services\PublicWebsite\PublicWebsiteGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;

use function sprintf;

class HugoWebsiteGeneratorJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function handle(PublicWebsiteGenerator $websiteGenerator): void
    {
        AdminLog::timedLog(
            static function () use ($websiteGenerator): void {
                $websiteGenerator->generate();
            },
            sprintf('Processed job: "%s"', Str::of(self::class)->afterLast('\\')),
        );
    }
}
