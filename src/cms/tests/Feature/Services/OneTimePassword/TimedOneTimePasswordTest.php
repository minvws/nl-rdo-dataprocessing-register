<?php

declare(strict_types=1);

use App\Services\OneTimePassword\TimedOneTimePassword;
use App\ValueObjects\OneTimePassword\Code;
use App\ValueObjects\OneTimePassword\Secret;
use Illuminate\Support\Facades\App;
use OTPHP\TOTP;

it('returns false if invalid', function (): void {
    /** @var TimedOneTimePassword $timedOneTimePassword */
    $timedOneTimePassword = App::make(TimedOneTimePassword::class);

    $isValid = $timedOneTimePassword->isCodeValid(Code::fromString(fake()->word()), Secret::fromString(fake()->word()));

    expect($isValid)
        ->toBeFalse();
});

it('returns true if valid', function (): void {
    $totp = TOTP::generate();
    $secret = $totp->getSecret();

    /** @var TimedOneTimePassword $timedOneTimePassword */
    $timedOneTimePassword = App::make(TimedOneTimePassword::class);

    $isValid = $timedOneTimePassword->isCodeValid(Code::fromString($totp->now()), Secret::fromString($secret));

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
        Secret::fromString(fake()->safeEmail()),
    );

    expect($qrCode)
        ->toStartWith('<svg')
        ->toEndWith('</svg>');
});
