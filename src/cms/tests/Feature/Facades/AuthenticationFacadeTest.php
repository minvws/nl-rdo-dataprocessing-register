<?php

declare(strict_types=1);

use App\Facades\Authentication;
use App\Models\User;
use Filament\Facades\Filament;

it('returns the user', function (): void {
    $user = User::factory()->create();
    $this->be($user);

    expect(Authentication::user())
        ->toBe($user);
});

it('returns the organisation', function (): void {
    $user = User::factory()->withOrganisation()->create();
    $this->be($user);
    Filament::setTenant($user->organisations->firstOrFail());

    expect(Authentication::organisation())
        ->toBe($user->organisations->firstOrFail());
});
