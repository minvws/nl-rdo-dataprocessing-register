<?php

declare(strict_types=1);

use App\Components\Uuid\Uuid;
use App\Enums\RouteName;
use App\Filament\Pages\Login;
use App\Models\User;
use App\Models\UserLoginToken;
use Illuminate\Support\Facades\URL;
use Tests\Helpers\ConfigTestHelper;

it('can show the login page', function (): void {
    $response = $this->get('/login');
    $response->assertOk();
});

it('accepts a valid email on the login page', function (): void {
    $user = User::factory()->withOrganisation()->create();

    $this->createLivewireTestable(Login::class)
        ->fillForm([
            'email' => $user->email,
        ])
        ->call('authenticate')
        ->assertNotified(__('auth.login_sent'));
});

it('accepts a valid uppercased-email on the login page', function (): void {
    $user = User::factory()->withOrganisation()->create();

    $this->createLivewireTestable(Login::class)
        ->fillForm([
            'email' => ucfirst($user->email),
        ])
        ->call('authenticate')
        ->assertNotified(__('auth.login_sent'));
});

it('does not accept an invalid email on the login page', function (): void {
    $this->createLivewireTestable(Login::class)
        ->fillForm([
            'email' => fake()->email(),
        ])
        ->call('authenticate')
        ->assertNotified(__('auth.login_sent'));
});

it('does not accept a valid email on the login page if no organisations attached', function (): void {
    $user = User::factory()->create();
    $user->organisations()->sync([]);

    $this->createLivewireTestable(Login::class)
        ->fillForm([
            'email' => $user->email,
        ])
        ->call('authenticate')
        ->assertNotified(__('auth.login_sent'));
});

it('does not notify if the rate limit is hit', function (): void {
    ConfigTestHelper::set('auth.passwordless.throttle.max_attempts', 1);

    $this->createLivewireTestable(Login::class)
        ->fillForm([
            'email' => fake()->email(),
        ])
        ->call('authenticate')
        ->call('authenticate')
        ->assertNotNotified(__('auth.email_sent'));
});

it('can consume a valid magic login link and a valid token', function (): void {
    $user = User::factory()->withOrganisation()->create();
    $token = Uuid::generate()->toString();

    $user->userLoginTokens()->create([
        'token' => $token,
        'expires_at' => now()->addMinutes(5),
    ]);
    $url = URL::signedRoute(RouteName::PASSWORDLESS_LOGIN_VALIDATE_CONSUME, [
        'token' => $token,
    ]);

    $this->get($url)
        ->assertViewIs('auth.consume');
    $this->assertGuest();
});

it('cannot consume a valid magic login link if no organisation attached', function (): void {
    $user = User::factory()->create();
    $user->organisations()->sync([]);
    $token = Uuid::generate()->toString();

    $user->userLoginTokens()->create([
        'token' => $token,
        'expires_at' => now()->addMinutes(5),
    ]);
    $url = URL::signedRoute(RouteName::PASSWORDLESS_LOGIN_VALIDATE_CONSUME, [
        'token' => $token,
    ]);

    $this->get($url)
        ->assertRedirect('/');
    $this->assertGuest();
});

it('cannot consume a valid magic login link and an invalid token', function (): void {
    $user = User::factory()->create();
    $user->userLoginTokens()->create([
        'token' => Uuid::generate()->toString(),
        'expires_at' => now()->addMinutes(5),
    ]);

    $url = URL::signedRoute(RouteName::PASSWORDLESS_LOGIN_VALIDATE_CONSUME, [
        'token' => Uuid::generate()->toString(),
    ]);

    $this->get($url)
        ->assertRedirect('/');
    $this->assertGuest();
});

it('cannot consume a valid magic login link and a null token', function (): void {
    $user = User::factory()->create();
    $user->userLoginTokens()->create([
        'token' => Uuid::generate()->toString(),
        'expires_at' => now()->addMinutes(5),
    ]);

    $url = URL::signedRoute(RouteName::PASSWORDLESS_LOGIN_VALIDATE_CONSUME, [
        'token' => null,
    ]);

    $this->get($url)
        ->assertRedirect('/');
    $this->assertGuest();
});

it('cannot consume a valid magic login link and an expired token', function (): void {
    $user = User::factory()->create();
    $token = Uuid::generate()->toString();

    $user->userLoginTokens()->create([
        'token' => $token,
        'expires_at' => now()->subMinutes(5),
    ]);

    $url = URL::signedRoute(RouteName::PASSWORDLESS_LOGIN_VALIDATE_CONSUME, [
        'token' => $token,
    ]);

    $this->get($url)
        ->assertRedirect('/');
    $this->assertGuest();
});

it('cannot consume an invalid magic login link', function (): void {
    $user = User::factory()->create();
    $token = Uuid::generate()->toString();

    $user->userLoginTokens()->create([
        'token' => $token,
        'expires_at' => now()->addMinutes(5),
    ]);

    $url = URL::signedRoute(RouteName::PASSWORDLESS_LOGIN_VALIDATE_CONSUME, [
        'token' => $token,
    ]);
    $url = str_replace('signature=', 'signature=invalid', $url);

    $this->get($url)
        ->assertStatus(403);
    $this->assertGuest();
});

it('cannot consume a magic login link for a non-existing user', function (): void {
    $url = URL::signedRoute(RouteName::PASSWORDLESS_LOGIN_VALIDATE_CONSUME, [
        'token' => Uuid::generate()->toString(),
    ]);

    $this->get($url)
        ->assertRedirect('/');
    $this->assertGuest();
});

it('does not delete the token if login is being consumed', function (): void {
    $user = User::factory()->withOrganisation()->create();
    $token = Uuid::generate()->toString();

    $user->userLoginTokens()->create([
        'token' => $token,
        'expires_at' => now()->addMinutes(5),
    ]);

    $url = URL::signedRoute(RouteName::PASSWORDLESS_LOGIN_VALIDATE_CONSUME, [
        'token' => $token,
    ]);
    $this->get($url);

    $this->assertDatabaseHas(UserLoginToken::class, [
        'token' => $token,
        'user_id' => $user->id,
    ]);
});

it('can process the login confirm form', function (): void {
    $user = User::factory()->withOrganisation()->create();
    $token = Uuid::generate()->toString();

    $user->userLoginTokens()->create([
        'token' => $token,
        'expires_at' => now()->addMinutes(5),
    ]);

    $url = URL::signedRoute(RouteName::PASSWORDLESS_LOGIN_VALIDATE_CONFIRM, [
        'token' => $token,
    ]);
    $this->post($url)
        ->assertRedirect('/');
    $this->assertAuthenticated();

    $this->assertDatabaseMissing(UserLoginToken::class, [
        'token' => $token,
        'user_id' => $user->id,
    ]);
});

it('cannot process the login confirm form with invalid token', function (): void {
    $user = User::factory()->withOrganisation()->create();
    $token = Uuid::generate()->toString();

    $user->userLoginTokens()->create([
        'token' => $token,
        'expires_at' => now()->addMinutes(5),
    ]);

    $url = URL::signedRoute(RouteName::PASSWORDLESS_LOGIN_VALIDATE_CONFIRM, [
        'token' => fake()->uuid(),
    ]);
    $this->post($url)
        ->assertRedirect('/');
    $this->assertGuest();

    $this->assertDatabaseHas(UserLoginToken::class, [
        'token' => $token,
        'user_id' => $user->id,
    ]);
});
