<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Models\User;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('loads the edit page', function (): void {
    $user = User::factory()
        ->create();

    $this->asFilamentUser()
        ->get(UserResource::getUrl('edit', ['record' => $user]))
        ->assertSuccessful()
        ->assertDontSeeText(__('user.password'));
});

it('loads the edit page if user has a global role', function (): void {
    $user = User::factory()
        ->hasGlobalRole(fake()->randomElement(Role::cases()))
        ->create();

    $this->asFilamentUser()
        ->get(UserResource::getUrl('edit', ['record' => $user]))
        ->assertSuccessful()
        ->assertDontSeeText(__('user.password'));
});

it('loads the edit page if user has an organisation role', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = User::factory()
        ->hasOrganisationRole(fake()->randomElement(Role::cases()), $organisation)
        ->create();

    $this->asFilamentUser()
        ->get(UserResource::getUrl('edit', ['record' => $user]))
        ->assertSuccessful()
        ->assertDontSeeText(__('user.password'));
});

it('loads the edit page if user has both a global role and an organisation role', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = User::factory()
        ->hasGlobalRole(fake()->randomElement(Role::cases()))
        ->hasOrganisationRole(fake()->randomElement(Role::cases()), $organisation)
        ->create();

    $this->asFilamentUser()
        ->get(UserResource::getUrl('edit', ['record' => $user]))
        ->assertSuccessful()
        ->assertDontSeeText(__('user.password'));
});

it('saves global roles', function (bool $setChiefPrivacyOfficer, bool $setFunctionalManager): void {
    $user = UserTestHelper::create();

    expect($user->globalRoles()->get()->count())
        ->toBe(0);

    $this->asFilamentUser()
        ->createLivewireTestable(EditUser::class, ['record' => $user->id])
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
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);

    expect($user->organisationRoles()->get()->count())
        ->toBe(0);

    $this->asFilamentUser()
        ->createLivewireTestable(EditUser::class, ['record' => $user->id])
        ->fillForm([
            sprintf('organisation_user_roles.%s.organisation_id', $organisation->id->toString()) => $organisation->id->toString(),
            sprintf('organisation_user_roles.%s.organisation_user_roles.input-processor', $organisation->id) => $isInputProcessor,
            sprintf('organisation_user_roles.%s.organisation_user_roles.privacy-officer', $organisation->id) => $isPrivacyOfficer,
            sprintf(
                'organisation_user_roles.%s.organisation_user_roles.data-protection-official',
                $organisation->id->toString(),
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
    $user = UserTestHelper::create();
    $name = fake()->unique()->name();
    $email = fake()->unique()->safeEmail();

    $this->asFilamentUser()
        ->createLivewireTestable(EditUser::class, ['record' => $user->id])
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
    $user = User::factory()->create();

    $this->asFilamentUser()
        ->createLivewireTestable(EditUser::class, ['record' => $user->id])
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
        ->create([
            'otp_secret' => $otpSecret,
        ]);

    expect($user->otp_secret)
        ->toBe($otpSecret);

    $this->asFilamentUser()
        ->createLivewireTestable(EditUser::class, [
            'record' => $user->id,
        ])
        ->callAction('otp_disable')
        ->assertHasNoErrors()
        ->assertNotified(__('user.one_time_password.disabled'));

    $user->refresh();
    expect($user->otp_secret)
        ->toBeNull();
});
