<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Middleware;

use App\Enums\Authorization\Role;
use App\Http\Middleware\IPAllowFilter;
use App\Models\Organisation;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\Helpers\ConfigTestHelper;

use function expect;
use function fake;
use function it;

it('allows access when the tenant is not set', function (): void {
    $request = $this->mock(Request::class)
        ->shouldReceive('ip')
        ->never()
        ->getMock();
    $middleware = new IPAllowFilter();

    $response = $middleware->handle($request, static function (): Response {
        return new Response('OK');
    });

    expect($response->getStatusCode())
        ->toBe(200);
});

it('rejects access when no IP is configured on the organisation or config', function (): void {
    ConfigTestHelper::set('app.allowed_ips', null);

    $organisation = Organisation::factory()
        ->create([
            'allowed_ips' => '',
        ]);
    $user = User::factory()
        ->hasAttached($organisation)
        ->hasGlobalRole(Role::FUNCTIONAL_MANAGER)
        ->create();

    $this->be($user);
    Filament::setTenant($organisation);

    $request = $this->mock(Request::class)
        ->shouldReceive('ip')
        ->once()
        ->andReturn(fake()->ipv4())
        ->getMock();
    $middleware = new IPAllowFilter();

    expect(static function () use ($middleware, $request): Response {
        return $middleware->handle($request, function (): Response {
            return new Response('OK'); // ignored
        });
    })
        ->toThrow(AuthorizationException::class, 'Access denied');
});

it('rejects access when the IP is not correctly configured on the organisation or config', function (): void {
    ConfigTestHelper::set('app.allowed_ips', fake()->ipv6());

    $organisation = Organisation::factory()
        ->create([
            'allowed_ips' => fake()->ipv4(),
        ]);
    $user = User::factory()
        ->hasAttached($organisation)
        ->hasGlobalRole(Role::FUNCTIONAL_MANAGER)
        ->create();

    $this->be($user);
    Filament::setTenant($organisation);

    $request = $this->mock(Request::class)
        ->shouldReceive('ip')
        ->once()
        ->andReturn(fake()->unique()->ipv4())
        ->getMock();
    $middleware = new IPAllowFilter();

    expect(static function () use ($middleware, $request): Response {
        return $middleware->handle($request, function (): Response {
            return new Response('OK'); // ignored
        });
    })
        ->toThrow(AuthorizationException::class, 'Access denied');
});

it('allows access when the IP is configured on the organisation', function (): void {
    $ip = fake()->ipv4();

    $organisation = Organisation::factory()
        ->create([
            'allowed_ips' => $ip,
        ]);
    $user = User::factory()
        ->hasAttached($organisation)
        ->hasGlobalRole(Role::FUNCTIONAL_MANAGER)
        ->create();

    $this->be($user);
    Filament::setTenant($organisation);

    $request = $this->mock(Request::class)
        ->shouldReceive('ip')
        ->once()
        ->andReturn($ip)
        ->getMock();
    $middleware = new IPAllowFilter();

    $response = $middleware->handle($request, static function (): Response {
        return new Response('OK');
    });

    expect($response->getStatusCode())
        ->toBe(200);
});

it('allows access when the IP is configured as config-value', function (): void {
    $ip = fake()->ipv4();
    ConfigTestHelper::set('app.allowed_ips', $ip);

    $organisation = Organisation::factory()
        ->create([
            'allowed_ips' => '',
        ]);
    $user = User::factory()
        ->hasAttached($organisation)
        ->hasGlobalRole(Role::FUNCTIONAL_MANAGER)
        ->create();

    $this->be($user);
    Filament::setTenant($organisation);

    $request = $this->mock(Request::class)
        ->shouldReceive('ip')
        ->once()
        ->andReturn($ip)
        ->getMock();
    $middleware = new IPAllowFilter();

    $response = $middleware->handle($request, static function (): Response {
        return new Response('OK');
    });

    expect($response->getStatusCode())
        ->toBe(200);
});
