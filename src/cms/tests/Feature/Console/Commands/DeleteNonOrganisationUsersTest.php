<?php

declare(strict_types=1);

use App\Models\User;

it('can run the command', function (): void {
    $user = User::factory()
        ->create();

    $this->artisan('app:delete-non-organisation-users')
        ->assertSuccessful();

    $user->refresh();

    expect($user->trashed())
        ->toBeTrue();
});
