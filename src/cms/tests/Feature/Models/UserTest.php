<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Mail\User\UserCreatedMailable;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

it('cannot access a tenant when the model passed is not an Organisation', function (): void {
    $user = User::factory()->create();

    expect($user->canAccessTenant(User::factory()->create()))
        ->toBeFalse();
});

it('can assign a global role', function (): void {
    $user = User::factory()->create();

    $globalRoles = $user->globalRoles()
        ->count();
    expect($globalRoles)
        ->toBe(0);

    $user->assignGlobalRole(Role::FUNCTIONAL_MANAGER);

    $globalRoles = $user->globalRoles()
        ->count();
    expect($globalRoles)
        ->toBe(1);
});

it('can assign an organisation role', function (): void {
    $user = User::factory()->create();
    $organisation = Organisation::factory()->create();

    $organisationRoleCount = $user->organisationRoles()
        ->where('organisation_id', $organisation->id)
        ->count();
    expect($organisationRoleCount)
        ->toBe(0);

    $user->assignOrganisationRole(Role::INPUT_PROCESSOR, $organisation);

    $organisationRoleCount = $user->organisationRoles()
        ->where('organisation_id', $organisation->id)
        ->count();
    expect($organisationRoleCount)
        ->toBe(1);
});

it('syncs only the manageable organisation roles of the given organisation', function (): void {
    $user = User::factory()->create();
    $organisation = Organisation::factory()->create();
    $otherOrganisation = Organisation::factory()->create();
    $user->assignOrganisationRole(Role::INPUT_PROCESSOR, $organisation);
    $user->assignOrganisationRole(Role::COUNSELOR, $organisation);
    $user->assignOrganisationRole(Role::INPUT_PROCESSOR, $otherOrganisation);

    $user->syncOrganisationRoles($organisation, [Role::INPUT_PROCESSOR, Role::PRIVACY_OFFICER], [Role::PRIVACY_OFFICER]);

    expect($user->organisationRoles()->where('organisation_id', $organisation->id)->pluck('role')->all())
        ->toEqualCanonicalizing([Role::COUNSELOR, Role::PRIVACY_OFFICER])
        ->and($user->organisationRoles()->where('organisation_id', $otherOrganisation->id)->pluck('role')->all())
        ->toBe([Role::INPUT_PROCESSOR]);
});

it('only syncs the mandate-holder-manager role together with the privacy officer role', function (array $roles, bool $expected): void {
    $user = User::factory()->create();
    $organisation = Organisation::factory()->create();

    $user->syncOrganisationRoles($organisation, Role::organisationRoles(), $roles);

    expect($user->organisationRoles()->where('role', Role::MANDATE_HOLDER_MANAGER->value)->exists())
        ->toBe($expected);
})->with([
    'with privacy officer' => [[Role::MANDATE_HOLDER_MANAGER, Role::PRIVACY_OFFICER], true],
    'without privacy officer' => [[Role::MANDATE_HOLDER_MANAGER, Role::INPUT_PROCESSOR], false],
]);

it('keeps the mandate-holder-manager role when the privacy officer role is outside the manageable roles', function (): void {
    $user = User::factory()->create();
    $organisation = Organisation::factory()->create();
    $user->assignOrganisationRole(Role::PRIVACY_OFFICER, $organisation);

    $user->syncOrganisationRoles($organisation, [Role::MANDATE_HOLDER_MANAGER], [Role::MANDATE_HOLDER_MANAGER]);

    expect($user->organisationRoles()->where('role', Role::MANDATE_HOLDER_MANAGER->value)->exists())
        ->toBeTrue();
});

it('removes the mandate-holder-manager role when the privacy officer role is removed', function (): void {
    $user = User::factory()->create();
    $organisation = Organisation::factory()->create();
    $user->assignOrganisationRole(Role::PRIVACY_OFFICER, $organisation);
    $user->assignOrganisationRole(Role::MANDATE_HOLDER_MANAGER, $organisation);

    $user->syncOrganisationRoles($organisation, [Role::PRIVACY_OFFICER], []);

    expect($user->organisationRoles()->where('organisation_id', $organisation->id)->exists())
        ->toBeFalse();
});

it('will not contain sensitive information on serialization', function (string $key, bool $expectedResult): void {
    $user = User::factory()->create();

    $serialized = json_encode($user);
    $unserialized = json_decode($serialized);

    expect(property_exists($unserialized, $key))
        ->toBe($expectedResult);
})->with([
    ['id', true],
    ['email', true],
    ['remember_token', false],
    ['otp_secret', false],
]);

it('will send email on user creation', function (): void {
    Mail::fake();

    User::factory()->create();

    Mail::assertQueued(UserCreatedMailable::class);
});

it('will return the correct value for logName', function (): void {
    $user = User::factory()->create([
        'name' => 'foo',
        'email' => 'bar@baz.com',
    ]);

    expect($user->logName)
        ->toBe('foo (bar@baz.com)');
});

it('will return the correct values for the audit-logger', function (): void {
    $role = fake()->randomElement(Role::cases());

    $user = User::factory()
        ->hasGlobalRole($role)
        ->create();

    expect($user->getAuditId())
        ->toBe($user->id->toString())
        ->and($user->getName())
        ->toBe($user->name)
        ->and($user->getRoles())
        ->toBe([$role->value])
        ->and($user->getEmail())
        ->toBe($user->email);
});
