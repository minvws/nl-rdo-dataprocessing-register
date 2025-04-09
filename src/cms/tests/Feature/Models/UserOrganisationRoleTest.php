<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;

it('stores the user organisation role', function (): void {
    $user = User::factory()->create();
    $organisation = Organisation::factory()->create();

    $user->assignOrganisationRole(Role::INPUT_PROCESSOR, $organisation);

    $this->assertDatabaseHas(UserOrganisationRole::class, [
        'user_id' => $user->id,
        'organisation_id' => $organisation->id,
    ]);
});

it('can retrieve the user from the user organisation role', function (): void {
    $user = User::factory()->create();
    $organisation = Organisation::factory()->create();

    $user->assignOrganisationRole(Role::INPUT_PROCESSOR, $organisation);

    $userOrganisationRole = UserOrganisationRole::query()
        ->where([
            'user_id' => $user->id,
            'organisation_id' => $organisation->id,
        ])
        ->firstOrFail();

    expect($userOrganisationRole->user->id)
        ->toBe($user->id);
});
