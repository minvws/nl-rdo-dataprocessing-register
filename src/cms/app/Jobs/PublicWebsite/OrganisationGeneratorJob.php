<?php

declare(strict_types=1);

namespace App\Jobs\PublicWebsite;

use App\Enums\Queue;
use App\Facades\AdminLog;
use App\Models\Organisation;
use App\Services\PublicWebsite\Content\OrganisationGenerator;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;

use function sprintf;

class OrganisationGeneratorJob implements ShouldQueue, ShouldBeUnique
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function __construct(
        public Organisation $organisation,
    ) {
        $this->onQueue(Queue::LOW);
    }

    /**
     * @throws Exception
     */
    public function handle(OrganisationGenerator $organisationGenerator): void
    {
        AdminLog::timedLog(
            function () use ($organisationGenerator): void {
                $organisationGenerator->generate($this->organisation);
            },
            sprintf('Processed job: "%s"', Str::of(self::class)->afterLast('\\')),
        );
    }
}
