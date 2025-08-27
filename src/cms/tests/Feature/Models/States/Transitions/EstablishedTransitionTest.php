<?php

declare(strict_types=1);

namespace Tests\Feature\Models\States\Transitions;

use App\Models\Snapshot;
use App\Models\States\Snapshot\Approved;
use App\Models\States\Snapshot\Established;
use App\Models\User;

use function expect;
use function it;

it('can transition to established', function (): void {
    $this->be(User::factory()->create());

    $snapshot = Snapshot::factory()->create([
        'state' => Approved::class,
    ]);
    $snapshot->state->transitionTo(Established::class);
    $snapshot->refresh();

    expect($snapshot->state)
        ->toBeInstanceOf(Established::class);
});
