<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners\Media;

use App\Events\Models\PublishableEvent;
use App\Models\Snapshot;
use Illuminate\Support\Facades\Event;

use function fake;
use function it;

it('triggers the event when updated', function (): void {
    $snapshot = Snapshot::factory()
        ->create([
            'name' => fake()->uuid(),
        ]);

    Event::fake(PublishableEvent::class);

    $snapshot->name = fake()->unique()->uuid();
    $snapshot->save();

    Event::assertDispatched(PublishableEvent::class);
});
