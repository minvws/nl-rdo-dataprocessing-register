<?php

declare(strict_types=1);

use App\Models\UserLoginToken;

it('can run the command', function (): void {
    $this->artisan('user:delete-expired-login-tokens')
        ->expectsOutput('Deleting expired user login tokens...')
        ->expectsOutput('0 expired user login tokens deleted.')
        ->assertExitCode(0);
});

it('prunes expired passwordless login tokens', function (): void {
    UserLoginToken::factory()->create([
        'expires_at' => now()->subMinute(),
    ]);

    $this->artisan('user:delete-expired-login-tokens')
        ->expectsOutput('Deleting expired user login tokens...')
        ->expectsOutput('1 expired user login tokens deleted.')
        ->assertExitCode(0);
});
