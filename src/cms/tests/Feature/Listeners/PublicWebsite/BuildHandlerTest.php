<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners\Media;

use App\Events\PublicWebsite\BuildEvent;
use App\Jobs\PublicWebsite\ContentGeneratorJob;
use App\Jobs\PublicWebsite\HugoWebsiteGeneratorJob;
use App\Jobs\PublicWebsite\PublicWebsiteCheckJob;
use App\Services\BuildContextService;
use Illuminate\Support\Facades\Bus;

use function it;

it('calls the BuildHandler that chains the jobs', function (): void {
    Bus::fake();
    BuildEvent::dispatch();

    Bus::assertChained([
        ContentGeneratorJob::class,
        HugoWebsiteGeneratorJob::class,
        PublicWebsiteCheckJob::class,
    ]);
});

it('calls the BuildHandler that chains the jobs when explicitly enabled', function (): void {
    $buildContextService = $this->app->get(BuildContextService::class);
    $buildContextService->enableBuild();

    Bus::fake();
    BuildEvent::dispatch();

    Bus::assertChained([
        ContentGeneratorJob::class,
        HugoWebsiteGeneratorJob::class,
        PublicWebsiteCheckJob::class,
    ]);
});

it('calls the BuildHandler but does not chain the jobs when context is disabled', function (): void {
    Bus::fake();
    $buildContextService = $this->app->get(BuildContextService::class);
    $buildContextService->disableBuild();

    BuildEvent::dispatch();

    Bus::assertNothingChained();
});
