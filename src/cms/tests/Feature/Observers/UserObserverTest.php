<?php

declare(strict_types=1);

namespace Tests\Feature\Observers;

use App\Models\Organisation;
use App\Models\User;

use function expect;
use function it;

it('will not delete the user if no longer attached to any organisation', function (): void {
    $organisation = Organisation::factory()
        ->create();
    $user = User::factory()
        ->hasAttached($organisation)
        ->create();

    expect($user->trashed())
        ->toBeFalse();

    $user->organisations()->detach($organisation);
    $user->save();
    $user->refresh();

    expect($user->trashed())
        ->toBeFalse();
});
