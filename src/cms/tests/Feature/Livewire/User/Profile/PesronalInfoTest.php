<?php

declare(strict_types=1);

use App\Livewire\User\Profile\PersonalInfo;

use function Pest\Livewire\livewire;

it('can mount the component', function (): void {
    livewire(PersonalInfo::class, [
        'user' => $this->user,
    ])->assertSee(__('user.profile.personal_info.heading'));
});

it('can submit the form', function (): void {
    $name = fake()->name();

    livewire(PersonalInfo::class, [
        'user' => $this->user,
    ])
        ->set('data.name', $name)
        ->call('submit')
        ->assertNotified(__('user.profile.personal_info.notify'));

    $this->user->refresh();
    expect($this->user->name)
        ->toBe($name);
});
