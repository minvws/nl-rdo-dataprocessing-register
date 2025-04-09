<?php

declare(strict_types=1);

use App\Models\User;

it('can reset otp', function (): void {
    $otpSecret = fake()->regexify('[A-Z]{16}');

    $user = User::factory()
        ->create([
            'otp_secret' => $otpSecret,
        ]);

    expect($user->otp_secret)
        ->toBe($otpSecret);

    $this->artisan('app:user-disable-otp')
        ->expectsQuestion('Email address', $user->email)
        ->assertSuccessful();

    $user->refresh();
    expect($user->otp_secret)
        ->toBeNull();
});

it('fails with an unknown email address', function (): void {
    $this->artisan('app:user-disable-otp')
        ->expectsQuestion('Email address', fake()->safeEmail())
        ->assertFailed();
});
