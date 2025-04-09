<?php

declare(strict_types=1);

it('can run a schedule', function (): void {
    $this->artisan('schedule:test --name app:prune-expired-user-login-tokens')
        ->assertSuccessful();
});
