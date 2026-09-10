<?php

declare(strict_types=1);

it('can run a schedule', function (): void {
    $this->artisan('schedule:test --name user:delete-expired-login-tokens')
        ->assertSuccessful();
});

it('can run the form draft schedule', function (): void {
    $this->artisan('schedule:test --name form-draft:delete-expired')
        ->assertSuccessful();
});
