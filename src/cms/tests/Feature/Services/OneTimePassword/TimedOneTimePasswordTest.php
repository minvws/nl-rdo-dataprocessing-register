<?php

declare(strict_types=1);

use App\Services\OneTimePassword\TimedOneTimePassword;
use Illuminate\Support\Facades\App;
use OTPHP\TOTP;

it('returns false if invalid', function (): void {
    /** @var TimedOneTimePassword $timedOneTimePassword */
    $timedOneTimePassword = App::make(TimedOneTimePassword::class);

    $isValid = $timedOneTimePassword->isCodeValid(fake()->word(), fake()->word());

    expect($isValid)
        ->toBeFalse();
});

it('returns true if valid', function (): void {
    $totp = TOTP::generate();
    $secret = $totp->getSecret();

    /** @var TimedOneTimePassword $timedOneTimePassword */
    $timedOneTimePassword = App::make(TimedOneTimePassword::class);

    $isValid = $timedOneTimePassword->isCodeValid($totp->now(), $secret);

    expect($isValid)
        ->toBe(true);
});

it('can generate a secret key', function (): void {
    /** @var TimedOneTimePassword $timedOneTimePassword */
    $timedOneTimePassword = App::make(TimedOneTimePassword::class);

    $secretKey = $timedOneTimePassword->generateSecretKey();

    expect($secretKey)
        ->toBeString($secretKey);
});

it('can generate a qr code and returns an inline svg', function (): void {
    /** @var TimedOneTimePassword $timedOneTimePassword */
    $timedOneTimePassword = App::make(TimedOneTimePassword::class);

    $qrCode = $timedOneTimePassword->generateQRCodeInline(
        fake()->company(),
        fake()->safeEmail(),
    );

    expect($qrCode)
        ->toStartWith('<svg')
        ->toEndWith('</svg>');
});
