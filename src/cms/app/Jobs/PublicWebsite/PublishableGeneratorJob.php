<?php

declare(strict_types=1);

namespace App\Jobs\PublicWebsite;

use App\Facades\AdminLog;
use App\Models\Contracts\Publishable;
use App\Services\PublicWebsite\Content\PublishableGenerator;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;

use function sprintf;

class PublishableGeneratorJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function __construct(
        public Publishable $publishable,
    ) {
    }

    public function uniqueId(): string
    {
        return $this->publishable->getPublicIdentifier();
    }

    /**
     * @throws Exception
     */
    public function handle(PublishableGenerator $publishableGenerator): void
    {
        AdminLog::timedLog(
            function () use ($publishableGenerator): void {
                $publishableGenerator->generate($this->publishable);
            },
            sprintf('Processed job: "%s"', Str::of(self::class)->afterLast('\\')),
        );
    }
}
