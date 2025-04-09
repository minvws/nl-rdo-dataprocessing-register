<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Models\User;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user->assignGlobalRole(Role::FUNCTIONAL_MANAGER);
});

it('loads the edit page', function (): void {
    $this->get(UserResource::getUrl('edit', ['record' => $this->user->id]))
        ->assertSuccessful()
        ->assertDontSeeText(__('user.password'));
});

it('saves global roles', function (bool $setChiefPrivacyOfficer, bool $setFunctionalManager): void {
    $user = User::factory()
        ->hasAttached($this->organisation)
        ->create();

    expect($user->globalRoles()->get()->count())
        ->toBe(0);

    livewire(EditUser::class, ['record' => $user->id])
        ->fillForm([
            'user_global_roles.chief-privacy-officer' => $setChiefPrivacyOfficer,
            'user_global_roles.functional-manager' => $setFunctionalManager,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $user->refresh();

    expect($user->globalRoles()->where('role', 'chief-privacy-officer')->count())
        ->toBe($setChiefPrivacyOfficer ? 1 : 0)
        ->and($user->globalRoles()->where('role', 'functional-manager')->count())
        ->toBe($setFunctionalManager ? 1 : 0);
})->with([
    [true, true],
    [true, false],
    [false, true],
    [false, false],
]);

it('saves organisation roles', function (bool $isInputProcessor, bool $isPrivacyOfficer, bool $isDataProtectionOfficial): void {
    $user = User::factory()
        ->hasAttached($this->organisation)
        ->create();

    expect($user->organisationRoles()->get()->count())
        ->toBe(0);

    livewire(EditUser::class, ['record' => $user->id])
        ->fillForm([
            sprintf('user_organisation_roles.%s.organisation_id', $this->organisation->id) => $this->organisation->id,
            sprintf('user_organisation_roles.%s.user_organisation_roles.input-processor', $this->organisation->id) => $isInputProcessor,
            sprintf('user_organisation_roles.%s.user_organisation_roles.privacy-officer', $this->organisation->id) => $isPrivacyOfficer,
            sprintf(
                'user_organisation_roles.%s.user_organisation_roles.data-protection-official',
                $this->organisation->id,
            ) => $isDataProtectionOfficial,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $user->refresh();
    expect($user->organisationRoles()->where('role', 'input-processor')->count())
        ->toBe($isInputProcessor ? 1 : 0)
        ->and($user->organisationRoles()->where('role', 'privacy-officer')->count())
        ->toBe($isPrivacyOfficer ? 1 : 0)
        ->and($user->organisationRoles()->where('role', 'data-protection-official')->count())
        ->toBe($isDataProtectionOfficial ? 1 : 0);
})->with([
    [true, true, true],
    [true, true, false],
    [true, false, true],
    [true, false, false],
    [false, true, true],
    [false, true, false],
    [false, false, true],
    [false, false, false],
]);

it('can edit an entry', function (): void {
    $user = User::factory()
        ->hasAttached($this->organisation)
        ->create();
    $name = fake()->unique()->name();
    $email = fake()->unique()->safeEmail();

    livewire(EditUser::class, ['record' => $user->id])
        ->fillForm([
            'name' => $name,
            'email' => $email,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(User::class, [
        'name' => $name,
        'email' => $email,
    ]);
});

it('cannot edit an entry with a duplicate emailaddress', function (): void {
    $email = fake()->safeEmail();
    User::factory()->create([
        'email' => $email,
    ]);
    $user = User::factory()
        ->hasAttached($this->organisation)
        ->create();

    livewire(EditUser::class, ['record' => $user->id])
        ->fillForm([
            'name' => fake()->name(),
            'email' => $email,
        ])
        ->call('save')
        ->assertHasFormErrors(['email' => 'unique']);
});

it('can reset the otp', function (): void {
    $otpSecret = fake()->regexify('[A-Z]{16}');

    $user = User::factory()
        ->hasAttached($this->organisation)
        ->create([
            'otp_secret' => $otpSecret,
        ]);

    expect($user->otp_secret)
        ->toBe($otpSecret);

    livewire(EditUser::class, [
        'record' => $user->id,
    ])
        ->callAction('otp_disable')
        ->assertHasNoErrors()
        ->assertNotified(__('user.one_time_password.disabled'));

    $user->refresh();
    expect($user->otp_secret)
        ->toBeNull();
});
