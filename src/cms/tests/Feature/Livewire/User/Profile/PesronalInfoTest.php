<?php

declare(strict_types=1);

use App\Livewire\User\Profile\PersonalInfo;
use Tests\Helpers\Model\UserTestHelper;

it('can mount the component', function (): void {
    $user = UserTestHelper::create();

    $this->asFilamentUser($user)
        ->createLivewireTestable(PersonalInfo::class, [
            'user' => $user,
        ])
        ->assertSee(__('user.profile.personal_info.heading'));
});

it('can submit the form', function (): void {
    $name = fake()->name();

    $user = UserTestHelper::create();

    $this->asFilamentUser($user)
        ->createLivewireTestable(PersonalInfo::class, [
            'user' => $user,
        ])
        ->set('data.name', $name)
        ->call('submit')
        ->assertNotified(__('user.profile.personal_info.notify'));

    $user->refresh();
    expect($user->name)
        ->toBe($name);
});
