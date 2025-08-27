<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners\Media;

use App\Events\PublicWebsite\AfterBuildEvent;
use App\Jobs\PublicWebsite\AfterBuildHookJob;
use Illuminate\Queue\CallQueuedClosure;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Queue;
use Tests\Helpers\ConfigTestHelper;

use function fake;
use function it;

it('calls the AfterBuildHook', function (): void {
    Queue::fake();

    ConfigTestHelper::set('public-website.build_after_hook', fake()->word());

    AfterBuildEvent::dispatch();

    Queue::assertPushed(AfterBuildHookJob::class);
});

it('does not store the debounced chain if no hook set', function (): void {
    Bus::fake();

    ConfigTestHelper::set('public-website.build_after_hook', null);

    AfterBuildEvent::dispatch();

    Bus::assertDispatched(CallQueuedClosure::class, 0);
});
