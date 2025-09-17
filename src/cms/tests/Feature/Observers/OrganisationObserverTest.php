<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners\Media;

use App\Events\StaticWebsite\BuildEvent;
use App\Models\Organisation;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Event;

use function it;

it('does not dispatch the build-event if organisation is created', function (?CarbonInterface $publicFrom): void {
    Event::fake(BuildEvent::class);

    Organisation::factory()->create([
        'public_from' => $publicFrom,
    ]);

    Event::assertNotDispatched(BuildEvent::class);
})->with([
    'public_from null' => [null],
    'public_from yesterday' => [CarbonImmutable::yesterday()],
    'public_from tomorrow' => [CarbonImmutable::tomorrow()],
]);

it('dispatches the build-event if needed if organisation is edited', function (
    ?CarbonInterface $oldPublicFrom,
    ?CarbonInterface $newPublicFrom,
    bool $expectedEvent,
): void {
    $organisation = Organisation::factory()->createQuietly([
        'public_from' => $oldPublicFrom,
    ]);

    Event::fake(BuildEvent::class);

    $organisation->public_from = $newPublicFrom;
    $organisation->save();

    Event::assertDispatchedTimes(BuildEvent::class, (int) $expectedEvent);
})->with([
    'old public_from null, new public_from null' => [null, null, false],
    'old public_from null, new public_from yesterday' => [null, CarbonImmutable::yesterday(), true],
    'old public_from null, new public_from tomorrow' => [null, CarbonImmutable::tomorrow(), false],
    'old public_from yesterday, new public_from null' => [CarbonImmutable::yesterday(), null, true],
    'old public_from yesterday, new public_from yesterday' => [CarbonImmutable::now()->subDays(3), CarbonImmutable::yesterday(), true],
    'old public_from yesterday, new public_from tomorrow' => [CarbonImmutable::yesterday(), CarbonImmutable::tomorrow(), true],
    'old public_from tomorrow, new public_from null' => [CarbonImmutable::tomorrow(), null, false],
    'old public_from tomorrow, new public_from yesterday' => [CarbonImmutable::tomorrow(), CarbonImmutable::yesterday(), true],
    'old public_from tomorrow, new public_from tomorrow' => [CarbonImmutable::tomorrow(), CarbonImmutable::now()->addDays(3), false],
]);

it('dispatches the build-event if needed if organisation is deleted', function (?CarbonInterface $publicFrom, bool $expectedEvent): void {
    $organisation = Organisation::factory()->createQuietly([
        'public_from' => $publicFrom,
    ]);

    Event::fake(BuildEvent::class);

    $organisation->delete();

    Event::assertDispatchedTimes(BuildEvent::class, (int) $expectedEvent);
})->with([
    'public_from null' => [null, false],
    'public_from yesterday' => [CarbonImmutable::yesterday(), true],
    'public_from tomorrow' => [CarbonImmutable::tomorrow(), false],
]);
