<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Middleware;

use App\Http\Middleware\EnforceOneTimePassword;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\Helpers\ConfigTestHelper;

use function expect;
use function fake;
use function it;
use function sprintf;

it('allows access when two-factor disabled', function (): void {
    $user = User::factory()->create();
    $this->be($user);

    $request = mockRequest(fake()->slug);

    $middleware = new EnforceOneTimePassword();
    $response = $middleware->handle($request, function (): Response {
        return new Response('OK');
    });

    expect($response->getStatusCode())->toBe(200);
});

it('allows access on logout', function (): void {
    $user = User::factory()->create();
    $this->be($user);

    $request = mockRequest(fake()->slug);

    $middleware = new EnforceOneTimePassword();
    $response = $middleware->handle($request, function (): Response {
        return new Response('OK');
    });

    expect($response->getStatusCode())->toBe(200);
});

it('allows access on profile', function (): void {
    $user = User::factory()->withOrganisation()->create([
        'otp_confirmed_at' => null,
    ]);
    $this->be($user);

    $slug = $user->organisations()->first()->slug;

    $request = mockRequest(fake()->slug, ['tenant' => $slug]);
    $request->shouldReceive('routeIs')
        ->once()
        ->andReturn('profile');

    $middleware = new EnforceOneTimePassword();
    $response = $middleware->handle($request, function (): Response {
        return new Response('OK');
    });

    expect($response->getStatusCode())->toBe(200);
});

it('allows access if no tenant-id', function (): void {
    $user = User::factory()->create();
    $this->be($user);

    $request = mockRequest(fake()->slug, ['tenant' => null]);

    $middleware = new EnforceOneTimePassword();
    $response = $middleware->handle($request, function (): Response {
        return new Response('OK');
    });

    expect($response->getStatusCode())->toBe(200);
});

it('redirects to profile page if two-factor is not confirmed', function (): void {
    $user = User::factory()->withOrganisation()->create([
        'otp_confirmed_at' => null,
    ]);
    $this->be($user);

    $slug = $user->organisations()->first()->slug;

    $request = mockRequest(sprintf('%s/%s', $slug, fake()->slug()), ['tenant' => $slug]);
    $request->shouldReceive('routeIs')
        ->once()
        ->with('filament.admin.pages.profile')
        ->andReturn(false);

    $middleware = new EnforceOneTimePassword();
    $response = $middleware->handle($request, function (): Response {
        return new Response('OK');
    });

    expect($response->getStatusCode())
        ->toBe(302)
        ->and($response->isRedirect(sprintf('%s/%s/profile', ConfigTestHelper::get('app.url'), $slug)))
        ->toBeTrue();
});

it('redirects to two-factor page if no valid session', function (): void {
    $user = User::factory()
        ->withOrganisation()
        ->withValidOtpRegistration()
        ->create();
    $this->be($user);

    $slug = $user->organisations()->first()->slug;

    $request = mockRequest(sprintf('%s/%s', $slug, fake()->slug()), ['tenant' => $slug]);

    $middleware = new EnforceOneTimePassword();
    $response = $middleware->handle($request, function (): Response {
        return new Response('OK');
    });

    expect($response->getStatusCode())
        ->toBe(302)
        ->and($response->isRedirect(sprintf('%s/%s/two-factor-authentication?next=%%2F', ConfigTestHelper::get('app.url'), $slug)))
        ->toBeTrue();
});
