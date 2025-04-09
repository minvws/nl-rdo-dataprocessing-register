<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Filament\Resources\UserOrganisationResource\Pages\CreateUserOrganisation;
use App\Filament\Resources\UserResource;
use App\Models\User;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user->assignGlobalRole(Role::FUNCTIONAL_MANAGER);
});

it('loads the create page', function (): void {
    $this->get(UserResource::getUrl('create'))
        ->assertSuccessful();
});

it('can add a role for an new user', function (): void {
    $email = fake()->unique()->safeEmail();
    $role = fake()->randomElement([
        Role::INPUT_PROCESSOR,
        Role::PRIVACY_OFFICER,
        Role::COUNSELOR,
        Role::DATA_PROTECTION_OFFICIAL,
        Role::MANDATE_HOLDER,
        Role::CHIEF_PRIVACY_OFFICER,
    ]);

    $this->assertDatabaseMissing(User::class, [
        'email' => $email,
    ]);

    livewire(CreateUserOrganisation::class)
        ->fillForm([
            'email' => $email,
            $role->value => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $user = User::where('email', $email)->firstOrFail();
    $organisationRoles = $user->organisationRoles;

    expect($organisationRoles->count())
        ->toBe(1)
        ->and($organisationRoles->first()->organisation_id)
        ->toBe($this->organisation->id)
        ->and($organisationRoles->first()->role)
        ->toBe($role);
});

it('can add a role for an existing user that is not yet linked to the organisation', function (): void {
    $user = User::factory()
        ->create();
    $role = fake()->randomElement([
        Role::INPUT_PROCESSOR,
        Role::PRIVACY_OFFICER,
        Role::COUNSELOR,
        Role::DATA_PROTECTION_OFFICIAL,
        Role::MANDATE_HOLDER,
        Role::CHIEF_PRIVACY_OFFICER,
    ]);

    $this->assertDatabaseMissing('user_organisation_roles', [
        'role' => $role->value,
        'user_id' => $user->id,
        'organisation_id' => $this->organisation->id,
    ]);

    livewire(CreateUserOrganisation::class)
        ->fillForm([
            'email' => $user->email,
            $role->value => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('user_organisation_roles', [
        'role' => $role->value,
        'user_id' => $user->id,
        'organisation_id' => $this->organisation->id,
    ]);

    $user->refresh();
    $organisationRoles = $user->organisationRoles;
    expect($organisationRoles->count())
        ->toBe(1)
        ->and($organisationRoles->first()->organisation_id)
        ->toBe($this->organisation->id)
        ->and($organisationRoles->first()->role)
        ->toBe($role);
});

it('can not a role for an existing user that is not already linked to the organisation', function (): void {
    $user = User::factory()
        ->hasAttached($this->organisation)
        ->create();
    $role = fake()->randomElement([
        Role::INPUT_PROCESSOR,
        Role::PRIVACY_OFFICER,
        Role::COUNSELOR,
        Role::DATA_PROTECTION_OFFICIAL,
        Role::MANDATE_HOLDER,
        Role::CHIEF_PRIVACY_OFFICER,
    ]);

    livewire(CreateUserOrganisation::class)
        ->fillForm([
            'email' => $user->email,
            $role->value => true,
        ])
        ->call('create')
        ->assertHasFormErrors(['email']);
});
