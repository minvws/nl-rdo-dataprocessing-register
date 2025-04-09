<?php

declare(strict_types=1);

namespace App\Jobs\PublicWebsite;

use App\Facades\AdminLog;
use App\Models\Organisation;
use App\Services\PublicWebsite\Content\PublishableListGenerator;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;

use function sprintf;

class PublishableListGeneratorJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function __construct(
        public readonly Organisation $organisation,
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(PublishableListGenerator $publishableListGenerator): void
    {
        AdminLog::timedLog(
            function () use ($publishableListGenerator): void {
                $publishableListGenerator->generate($this->organisation);
            },
            sprintf('Processed job: "%s"', Str::of(self::class)->afterLast('\\')),
        );
    }
}
