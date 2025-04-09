<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Middleware;

use App\Enums\Authorization\Role;
use App\Http\Middleware\IPAllowFilter;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\Response;

use function expect;
use function it;

it('allows access when the tenant is not set', function (): void {
    $request = createRequestMock();
    $middleware = new IPAllowFilter();
    $response = $middleware->handle($request, function (): Response {
        return new Response('OK');
    });

    expect($response->getStatusCode())->toBe(200);
});

it('rejects access when the IP is not configured on the organisation', function (): void {
    $user = User::factory()
        ->withOrganisation()
        ->create()
        ->assignGlobalRole(Role::FUNCTIONAL_MANAGER);

    $organisation = $user->organisations()->firstOrFail();
    $organisation->allowed_ips = '';
    $organisation->save();

    $this->be($user);
    Filament::setTenant($organisation);

    $request = createRequestMock();
    $middleware = new IPAllowFilter();

    expect(fn () => $middleware->handle($request, function (): Response {
            return new Response('OK'); // ignored
    }))
        ->toThrow(AuthorizationException::class, 'Access denied');
});

it('allows access when the IP is configured on the organisation', function (): void {
    $user = User::factory()
        ->withOrganisation()
        ->create()
        ->assignGlobalRole(Role::FUNCTIONAL_MANAGER);

    $organisation = $user->organisations()->firstOrFail();
    $organisation->allowed_ips = '127.0.0.1';
    $organisation->save();

    $this->be($user);
    Filament::setTenant($organisation);

    $request = createRequestMock();
    $middleware = new IPAllowFilter();
    $response = $middleware->handle($request, function (): Response {
        return new Response('OK');
    });

    expect($response->getStatusCode())->toBe(200);
});

function createRequestMock(): MockInterface|LegacyMockInterface
{
    $request = Mockery::mock(Request::class);
    $request->shouldReceive('ip')->andReturn('127.0.0.1');

    return $request;
}
