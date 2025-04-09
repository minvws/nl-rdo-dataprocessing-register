<?php

declare(strict_types=1);

use App\Models\User;
use Filament\Facades\Filament;

it('loads the page', function (): void {
    $this->get(sprintf('%s/profile', $this->organisation->slug))
        ->assertSee(__('user.profile.my_profile'));
});

it('returns 404 on tenant/profile if no organisation attached', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(sprintf('%s/profile', $this->organisation->slug))
        ->assertNotFound();
});

it('returns 404 on /profile if no organisation attached', function (): void {
    Filament::setTenant(null);

    $this->actingAs($this->user)
        ->get('/profile')
        ->assertNotFound();
});
