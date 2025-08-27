<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners\Media;

use App\Events\StaticWebsite\BuildEvent;
use App\Jobs\StaticWebsite\ContentGeneratorJob;
use App\Jobs\StaticWebsite\HugoWebsiteGeneratorJob;
use Illuminate\Support\Facades\Bus;

use function it;

it('calls the BuildHandler that chains the jobs', function (): void {
    Bus::fake();
    BuildEvent::dispatch();

    Bus::assertChained([
        ContentGeneratorJob::class,
        HugoWebsiteGeneratorJob::class,
    ]);
});
