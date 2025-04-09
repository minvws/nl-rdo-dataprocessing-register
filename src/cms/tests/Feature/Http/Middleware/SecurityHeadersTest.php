<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Middleware;

use App\Enums\Authorization\Role;
use App\Models\Organisation;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Testing\TestResponse;

use function expect;
use function it;
use function Pest\Laravel\get;
use function setOtpValidSessionValue;
use function sprintf;

it('contains a csp header is in place', function (): void {
    $responseHeaderBag = get('login')->headers;
    $cspHeader = $responseHeaderBag->get('content-security-policy');

    expect($cspHeader)
        ->toContain("base-uri 'self'")
        ->toContain("connect-src 'self'")
        ->toContain("form-action 'self'")
        ->toContain("media-src 'self'");
});

it('does not container a referrrer header', function (): void {
    $organisation = Organisation::factory()->create();
    $user = User::factory()
        ->hasAttached($organisation)
        ->withValidOtpRegistration()
        ->create();
    $user->assignOrganisationRole(Role::PRIVACY_OFFICER, $organisation);

    $this->be($user);
    Filament::setTenant($organisation);

    $responseHeaderBag = get(sprintf('%s/categories/create', $organisation->slug))->headers;
    expect($responseHeaderBag->get('referrer-policy'))
        ->toBe('no-referrer');
});

it('does not accept a referrrer header for the cancel-button', function (): void {
    $organisation = Organisation::factory()->create();
    $user = User::factory()
        ->hasAttached($organisation)
        ->withValidOtpRegistration()
        ->create();
    setOtpValidSessionValue(true);
    $user->assignOrganisationRole(Role::PRIVACY_OFFICER, $organisation);

    $this->be($user);
    Filament::setTenant($organisation);

    $requestHeader = ['Referer' => 'hacker"><script>alert(document.cookie)</script>hacker'];

    /** @var TestResponse $response */
    $response = $this->get(sprintf('%s/categories/create', $organisation->slug), $requestHeader);
    $this->assertStringNotContainsString('hacker', $response->content());
});
