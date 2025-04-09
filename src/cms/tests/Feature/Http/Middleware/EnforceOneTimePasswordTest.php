<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Middleware;

use App\Http\Middleware\EnforceOneTimePassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Mockery;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\Helpers\ConfigHelper;

use function expect;
use function fake;
use function it;
use function sprintf;

it('allows access when two-factor disabled', function (): void {
    $user = User::factory()->create();
    $this->be($user);

    $request = mockEnforceOneTimePasswordRequest('profile');

    $middleware = new EnforceOneTimePassword();
    $response = $middleware->handle($request, function (): Response {
        return new Response('OK');
    });

    expect($response->getStatusCode())->toBe(200);
});

it('allows access on logout', function (): void {
    $user = User::factory()->create();
    $this->be($user);

    $request = mockEnforceOneTimePasswordRequest('logout');

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

    $request = mockEnforceOneTimePasswordRequest('profile', ['tenant' => $slug]);
    $request->shouldReceive('routeIs')
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

    $request = mockEnforceOneTimePasswordRequest('no-tenant', ['tenant' => null]);

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

    $request = mockEnforceOneTimePasswordRequest(sprintf('%s/%s', $slug, fake()->slug()), ['tenant' => $slug]);
    $request->shouldReceive('routeIs')
        ->with('filament.admin.pages.profile')
        ->andReturn(false);

    $middleware = new EnforceOneTimePassword();
    $response = $middleware->handle($request, function (): Response {
        return new Response('OK');
    });

    expect($response->getStatusCode())
        ->toBe(302)
        ->and($response->isRedirect(sprintf('%s/%s/profile', ConfigHelper::get('app.url'), $slug)))
        ->toBeTrue();
});

it('redirects to two-factor page if no valid session', function (): void {
    $user = User::factory()->withOrganisation()->withValidOtpRegistration()->create();
    $this->be($user);

    $slug = $user->organisations()->first()->slug;

    $request = mockEnforceOneTimePasswordRequest(sprintf('%s/%s', $slug, fake()->slug()), ['tenant' => $slug]);
    $request->shouldReceive('routeIs')
        ->with('filament.admin.pages.profile')
        ->andReturn(false);

    $middleware = new EnforceOneTimePassword();
    $response = $middleware->handle($request, function (): Response {
        return new Response('OK');
    });

    expect($response->getStatusCode())
        ->toBe(302)
        ->and($response->isRedirect(sprintf('%s/%s/two-factor-authentication?next=%%2F', ConfigHelper::get('app.url'), $slug)))
        ->toBeTrue();
});

/**
 * @param array<string> $parameters
 */
function mockEnforceOneTimePasswordRequest(string $uri, array $parameters = []): Request
{
    $route = new Route('get', $uri, ['as' => $uri]);
    $route->parameters = $parameters;

    /** @var Request|MockObject $request */
    $request = Mockery::mock(Request::class);
    $request->shouldReceive('route')
        ->andReturn($route);

    return $request;
}
