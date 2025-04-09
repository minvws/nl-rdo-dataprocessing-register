<?php

declare(strict_types=1);

use App\Models\Organisation;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\followingRedirects;
use function Pest\Laravel\get;

it('redirects to login', function (): void {
    Auth::logout();

    $this->get('/')
        ->assertRedirectToRoute('filament.admin.auth.login');
});

it('loads the main page', function (): void {
    followingRedirects()
        ->get('/')
        ->assertSee($this->organisation->name);
});

it('gets redirected to the users organisation tenant scoped main page', function (): void {
    get('/')
        ->assertRedirect(sprintf('/%s/avg-responsible-processing-records', $this->organisation->slug));
});

it('is not allowed to access an unauthorized organisation', function (): void {
    $unauthorizedOrganisation = Organisation::factory()->create();

    get(sprintf('/%s', $unauthorizedOrganisation->slug))
        ->assertNotFound();
});

it('shows 404 when trying to load a url for a non-existing organisation without valid otp session', function (): void {
    setOtpValidSessionValue(false);

    $this->get(sprintf('/%s/two-factor-authentication', fake()->slug()))
        ->assertNotFound();
});
