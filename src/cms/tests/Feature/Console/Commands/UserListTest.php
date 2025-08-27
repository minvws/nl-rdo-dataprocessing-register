<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands;

use App\Models\User;

use function it;

it('can list users', function (): void {
    $user = User::factory()->create();

    $this->artisan('user:list')
        ->assertOk()
        ->expectsTable(['Name', 'Email'], [[$user->name, $user->email]]);
});

it('can list users with filter', function (): void {
    $user = User::factory()->create();

    $this->artisan('user:list', ['--filter' => $user->email])
        ->assertOk()
        ->expectsTable(['Name', 'Email'], [[$user->name, $user->email]]);
});
