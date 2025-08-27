<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Models\Organisation;
use App\Models\OrganisationUserRole;
use App\Models\User;

it('stores the user organisation role', function (): void {
    $organisation = Organisation::factory()->create();
    $user = User::factory()
        ->hasOrganisationRole(Role::INPUT_PROCESSOR, $organisation)
        ->create();

    $this->assertDatabaseHas(OrganisationUserRole::class, [
        'user_id' => $user->id,
        'organisation_id' => $organisation->id,
    ]);
});

it('can retrieve the user from the user organisation role', function (): void {
    $organisation = Organisation::factory()
        ->create();
    $user = User::factory()
        ->hasOrganisationRole(Role::INPUT_PROCESSOR, $organisation)
        ->create();

    $organisationUserRole = OrganisationUserRole::query()
        ->where([
            'user_id' => $user->id,
            'organisation_id' => $organisation->id,
        ])
        ->firstOrFail();

    expect($organisationUserRole->user->id)
        ->toBe($user->id);
});
