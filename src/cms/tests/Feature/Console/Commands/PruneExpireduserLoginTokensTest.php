<?php

declare(strict_types=1);

use App\Models\UserLoginToken;

it('can run the command', function (): void {
    $this->artisan('app:prune-expired-user-login-tokens')
        ->expectsOutput('Pruning expired user login tokens...')
        ->expectsOutput('0 expired user login tokens pruned.')
        ->assertExitCode(0);
});

it('prunes expired passwordless login tokens', function (): void {
    UserLoginToken::factory()->create([
        'expires_at' => now()->subMinute(),
    ]);

    $this->artisan('app:prune-expired-user-login-tokens')
        ->expectsOutput('Pruning expired user login tokens...')
        ->expectsOutput('1 expired user login tokens pruned.')
        ->assertExitCode(0);
});
