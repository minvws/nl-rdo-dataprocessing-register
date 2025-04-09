<?php

declare(strict_types=1);

use App\Models\Snapshot;
use App\Models\States\Snapshot\InReview;

it('can display wireable properties', function (): void {
    $snapshot = Snapshot::factory()->create([
        'state' => InReview::class,
    ]);
    $state = fake()->word();

    expect($snapshot->state->toLivewire())
        ->toBe([
            'name' => InReview::$name,
            'color' => InReview::$color,
        ])
        ->and($snapshot->state->fromLivewire($state))
            ->toBe($state);
});
