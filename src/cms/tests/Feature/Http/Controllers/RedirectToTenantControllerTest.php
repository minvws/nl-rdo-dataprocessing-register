<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Enums\Authorization\Role;
use App\Models\Organisation;
use App\Models\User;

use function it;
use function sprintf;

it('redirects when no user is logged in', function (): void {
    $this->get('/')->assertRedirect('login');
});

it('shows 403 when no organisation', function (): void {
    $user = User::factory()
        ->withValidOtpRegistration()
        ->create();

    $this->actingAs($user)
        ->get('/')
        ->assertStatus(403);
});

it('shows 403 when no role in organisation', function (): void {
    $organisation = Organisation::factory()->create();
    $user = User::factory()
        ->hasAttached($organisation)
        ->withValidOtpRegistration()
        ->create();

    $this->actingAs($user)
        ->get('/')
        ->assertStatus(403);
});

it('redirects to organisation when user has organisation role', function (): void {
    $organisation = Organisation::factory()->create();
    $user = User::factory()
        ->hasAttached($organisation)
        ->withValidOtpRegistration()
        ->create();
    $user->assignOrganisationRole(Role::PRIVACY_OFFICER, $organisation);

    $this->actingAs($user)
        ->get('/')
        ->assertRedirect(sprintf('%s/avg-responsible-processing-records', $organisation->slug));
});


it('redirects to organisation when user has global role', function (): void {
    $organisation = Organisation::factory()->create();
    $user = User::factory()
        ->hasAttached($organisation)
        ->withValidOtpRegistration()
        ->create();
    $user->assignGlobalRole(Role::CHIEF_PRIVACY_OFFICER);

    $this->actingAs($user)
        ->get('/')
        ->assertRedirect(sprintf('%s/avg-responsible-processing-records', $organisation->slug));
});
