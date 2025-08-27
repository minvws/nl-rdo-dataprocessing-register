<?php

declare(strict_types=1);

namespace Tests\Feature\Models\Concerns;

use App\Enums\Authorization\Role;
use App\Filament\Actions\SnapshotTransition\ApproveAction;
use App\Filament\Actions\SnapshotTransition\EstablishAction;
use App\Filament\Actions\SnapshotTransition\InReviewAction;
use App\Filament\Actions\SnapshotTransition\ObsoleteAction;
use App\Models\Organisation;
use App\Models\Snapshot;
use App\Models\SnapshotTransition;
use App\Models\States\Snapshot\Approved;
use App\Models\States\Snapshot\Established;
use App\Models\States\Snapshot\InReview;
use App\Models\States\Snapshot\Obsolete;
use App\Models\User;
use Spatie\ModelStates\Exceptions\TransitionNotFound;

use function expect;
use function it;

it('returns the correct action', function (string $state, string $expectedAction): void {
    $snapshot = Snapshot::factory()->create(['state' => $state]);

    expect($snapshot->state->getAction())
        ->toBe($expectedAction);
})->with([
    [Approved::class, ApproveAction::class],
    [Established::class, EstablishAction::class],
    [InReview::class, InReviewAction::class],
    [Obsolete::class, ObsoleteAction::class],
]);

it('allows the transitions', function (string $state, string $newState): void {
    $snapshot = Snapshot::factory()->create(['state' => $state]);

    $organisation = Organisation::factory()->create();
    $user = User::factory()
        ->hasOrganisationRole(Role::PRIVACY_OFFICER, $organisation)
        ->create();
    $this->be($user);

    $snapshot->state->transitionTo($newState);

    expect($snapshot->state)->toBeInstanceOf($newState)
        ->and(SnapshotTransition::count())->toBe(1);
})->with([
    [InReview::class, Approved::class],
    [Approved::class, Established::class],
    [InReview::class, Obsolete::class],
    [Approved::class, Obsolete::class],
    [Established::class, Obsolete::class],
]);

it('does not allow the transitions', function (string $state, string $newState): void {
    $snapshot = Snapshot::factory()->create(['state' => $state]);

    $organisation = Organisation::factory()->create();
    $user = User::factory()
        ->hasOrganisationRole(Role::PRIVACY_OFFICER, $organisation)
        ->create();
    $this->be($user);

    $snapshot->state->transitionTo($newState);

    expect($snapshot->state)
        ->toBeInstanceOf($newState);
})->throws(TransitionNotFound::class)->with([
    [InReview::class, Established::class],
    [Approved::class, InReview::class],
    [Established::class, InReview::class],
    [Obsolete::class, InReview::class],
    [Obsolete::class, Approved::class],
    [Obsolete::class, Established::class],
]);
