<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Filament\Resources\UserOrganisationResource;
use App\Filament\Resources\UserOrganisationResource\Pages\EditUserOrganisation;
use App\Models\User;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user->assignGlobalRole(Role::FUNCTIONAL_MANAGER);
});

it('loads the edit page', function (): void {
    $this->get(UserOrganisationResource::getUrl('edit', ['record' => $this->user->id]))
        ->assertSuccessful();
});

it('can edit a role for a user that is already linked to the organisation', function (): void {
    $user = User::factory()
        ->hasAttached($this->organisation)
        ->create();
    $role = fake()->randomElement([
        Role::COUNSELOR,
        Role::DATA_PROTECTION_OFFICIAL,
        Role::INPUT_PROCESSOR,
        Role::MANDATE_HOLDER,
        Role::PRIVACY_OFFICER,
    ]);

    $this->assertDatabaseMissing('user_organisation_roles', [
        'role' => $role->value,
        'user_id' => $user->id,
        'organisation_id' => $this->organisation->id,
    ]);

    livewire(EditUserOrganisation::class, ['record' => $user->id])
        ->fillForm([
            $role->value => true,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $user->refresh();
    $organisationRoles = $user->organisationRoles;
    expect($organisationRoles->count())
        ->toBe(1)
        ->and($organisationRoles->first()->organisation_id)
        ->toBe($this->organisation->id)
        ->and($organisationRoles->first()->role)
        ->toBe($role);
});

it('can detach a user that is linked to an organisation', function (): void {
    $user = User::factory()
        ->hasAttached($this->organisation)
        ->create();
    $user->assignOrganisationRole(fake()->randomElement([
        Role::INPUT_PROCESSOR,
        Role::PRIVACY_OFFICER,
        Role::COUNSELOR,
        Role::DATA_PROTECTION_OFFICIAL,
        Role::MANDATE_HOLDER,
    ]), $this->organisation);

    expect($user->organisations->count())
        ->toBe(1);

    livewire(EditUserOrganisation::class, ['record' => $user->id])
        ->callAction('detach')
        ->assertHasNoFormErrors();

    $user->refresh();
    expect($user->organisations->count())
        ->toBe(0);
});
