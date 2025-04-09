<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners\Media;

use App\Events\PublicWebsite\AfterBuildEvent;
use App\Jobs\PublicWebsite\AfterBuildHookJob;
use App\Jobs\PublicWebsite\PublicWebsiteCheckJob;
use Carbon\CarbonImmutable;
use Illuminate\Queue\CallQueuedClosure;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Queue;
use Tests\Helpers\ConfigHelper;

use function count;
use function fake;
use function it;

it('calls the AfterBuildHook', function (): void {
    Queue::fake();

    ConfigHelper::set('public-website.build_after_hook', fake()->word());

    AfterBuildEvent::dispatch();

    Queue::assertPushed(AfterBuildHookJob::class);
});

it('does not store the debounced chain if no hook set', function (): void {
    Bus::fake();

    ConfigHelper::set('public-website.build_after_hook', null);

    AfterBuildEvent::dispatch();

    Bus::assertDispatched(CallQueuedClosure::class, 0);
});

it('plans the website checks', function (array $configuredJobDelays): void {
    Queue::fake();
    CarbonImmutable::setTestNow();

    ConfigHelper::set('public-website.build_after_hook', null); // disable hook (not needed for this test)
    ConfigHelper::set('public-website.plan-check-job-delays', $configuredJobDelays);

    AfterBuildEvent::dispatch();

    Queue::assertCount(count($configuredJobDelays));
    foreach ($configuredJobDelays as $configuredJobDelay) {
        Queue::assertPushed(
            PublicWebsiteCheckJob::class,
            static function (PublicWebsiteCheckJob $publicWebsiteCheckJob) use ($configuredJobDelay): bool {
                $expectedDelay = CarbonImmutable::now()->addMinutes($configuredJobDelay)->floorMinute();

                return $expectedDelay->equalTo($publicWebsiteCheckJob->delay);
            },
        );
    }
})->with([
    [[1]],
    [[1, 3, 5]],
]);
