<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Middleware;

use App\Models\Organisation;

use function expect;
use function it;
use function sprintf;

it('contains a csp header is in place', function (): void {
    $responseHeaderBag = $this->get('login')->headers;
    $cspHeader = $responseHeaderBag->get('content-security-policy');

    expect($cspHeader)
        ->toContain("base-uri 'self'")
        ->toContain("connect-src 'self'")
        ->toContain("form-action 'self'")
        ->toContain("media-src 'self'");
});

it('does not container a referrrer header', function (): void {
    $organisation = Organisation::factory()->create();

    $response = $this->asFilamentOrganisationUser($organisation)
        ->get(sprintf('%s/categories/create', $organisation->slug));

    expect($response->headers->get('referrer-policy'))
        ->toBe('no-referrer');
});

it('does not accept a referrrer header for the cancel-button', function (): void {
    $organisation = Organisation::factory()->create();

    $requestHeader = ['Referer' => 'hacker"><script>alert(document.cookie)</script>hacker'];

    $response = $this->asFilamentOrganisationUser($organisation)
        ->get(sprintf('%s/categories/create', $organisation->slug), $requestHeader);

    $this->assertStringNotContainsString('hacker', $response->content());
});
