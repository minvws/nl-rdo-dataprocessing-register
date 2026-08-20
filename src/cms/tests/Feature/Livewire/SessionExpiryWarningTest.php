<?php

declare(strict_types=1);

use App\Livewire\SessionExpiryWarning;
use Tests\Helpers\ConfigTestHelper;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('announces the end of the session before it happens', function (): void {
    $user = UserTestHelper::create();

    $this->asFilamentUser($user)
        ->createLivewireTestable(SessionExpiryWarning::class)
        ->assertSee(__('session.expiry_warning_heading'))
        ->assertSee(__('session.extend'));
});

it('counts down from the configured session lifetime', function (): void {
    $user = UserTestHelper::create();
    ConfigTestHelper::set('session.lifetime', 30);

    $this->asFilamentUser($user)
        ->createLivewireTestable(SessionExpiryWarning::class)
        ->assertSeeHtml('Date.now() + 1800 * 1000');
});

it('redirects to the login page with an expired-notice when the session has ended', function (): void {
    $user = UserTestHelper::create();

    $this->asFilamentUser($user)
        ->createLivewireTestable(SessionExpiryWarning::class)
        ->assertSeeHtml(SessionExpiryWarning::EXPIRED_QUERY_PARAMETER . '=1');
});

it('is possible for user to extend the session', function (): void {
    $user = UserTestHelper::create();

    $this->asFilamentUser($user)
        ->createLivewireTestable(SessionExpiryWarning::class)
        ->call('$refresh')
        ->assertHasNoErrors();
});

it('is rendered on the pages behind the login', function (): void {
    $organisation = OrganisationTestHelper::create();

    $this->asFilamentOrganisationUser($organisation)
        ->get(sprintf('%s/avg-responsible-processing-record-services', $organisation->slug))
        ->assertOk()
        ->assertSee(__('session.expiry_warning_heading'));
});

it('is not rendered on the login page', function (): void {
    $this->get('/login')
        ->assertOk()
        ->assertDontSee(__('session.expiry_warning_heading'));
});
