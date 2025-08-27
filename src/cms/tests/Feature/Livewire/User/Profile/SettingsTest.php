<?php

declare(strict_types=1);

use App\Enums\Authorization\Permission;
use App\Enums\RegisterLayout;
use App\Enums\Snapshot\MandateholderNotifyBatch;
use App\Enums\Snapshot\MandateholderNotifyDirectly;
use App\Livewire\User\Profile\Settings;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('can mount the component', function (): void {
    $user = UserTestHelper::create();

    $this->asFilamentUser()
        ->createLivewireTestable(Settings::class, [
            'user' => $user,
        ])
        ->assertSee(__('user.profile.settings.heading'));
});

it('can submit the form if no mandateholder-permission', function (): void {
    $registerLayout = fake()->randomElement(RegisterLayout::cases());

    $user = UserTestHelper::create();

    $this->asFilamentUser($user)
        ->createLivewireTestable(Settings::class, [
            'user' => $user,
        ])
        ->set('data.register_layout', $registerLayout->value)
        ->call('submit')
        ->assertNotified(__('user.profile.settings.notify'));

    $user->refresh();

    expect($user->register_layout)
        ->toBe($registerLayout);
});

it('can submit the form with the mandateholder-permissions', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions($organisation, [
        Permission::USER_PROFILE_SETTINGS_MANDATEHOLDER,
    ]);

    $mandateholderNotifyBatch = fake()->randomElement(MandateholderNotifyBatch::cases());
    $mandateholderNotifyDirectly = fake()->randomElement(MandateholderNotifyDirectly::cases());
    $registerLayout = fake()->randomElement(RegisterLayout::cases());

    $this->withFilamentSession($user, $organisation)
        ->createLivewireTestable(Settings::class, [
            'user' => $user,
        ])
        ->set('data.mandateholder_notify_batch', $mandateholderNotifyBatch->value)
        ->set('data.mandateholder_notify_directly', $mandateholderNotifyDirectly->value)
        ->set('data.register_layout', $registerLayout->value)
        ->call('submit')
        ->assertNotified(__('user.profile.settings.notify'));

    $user->refresh();

    expect($user->mandateholder_notify_batch)->toBe($mandateholderNotifyBatch)
        ->and($user->mandateholder_notify_directly)->toBe($mandateholderNotifyDirectly)
        ->and($user->register_layout)->toBe($registerLayout);
});
