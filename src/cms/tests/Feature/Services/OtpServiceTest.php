<?php

declare(strict_types=1);

use App\Models\User;
use App\Services\OneTimePassword\OneTimePassword;
use App\Services\OtpService;
use App\ValueObjects\OneTimePassword\Code;
use App\ValueObjects\OneTimePassword\Secret;
use Illuminate\Support\Facades\App;

use function PHPUnit\Framework\equalTo;

it('can enable two-factor authentication', function (): void {
    $user = User::factory()->create([
        'otp_secret' => null,
        'otp_confirmed_at' => null,
    ]);

    getOtpService()->enable($user);

    expect($user->otp_secret)
        ->toBeString()
        ->and($user->otp_confirmed_at)
        ->toBeNull();
});

it('can disable two-factor authentication', function (): void {
    $user = User::factory()
        ->withValidOtpRegistration()
        ->create();

    getOtpService()->disable($user);

    expect($user->otp_secret)
        ->toBeNull()
        ->and($user->otp_confirmed_at)
        ->toBeNull();
});

it('can generate the qr-code', function (): void {
    $user = User::factory()
        ->withValidOtpRegistration()
        ->create();

    $otpService = getOtpService();
    $qrCode = $otpService->generateQRCodeInline(Secret::fromString(fake()->sentence()), $user->otp_secret);

    expect($qrCode)
        ->toStartWith('<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="150" height="150" viewBox="0 0 150 150">')
        ->toEndWith('</svg>');
});

it('can validate a code', function (): void {
    $code = Code::fromString(fake()->word());
    $user = User::factory()->withValidOtpRegistration()->create();

    $this->mock(OneTimePassword::class)
        ->shouldReceive('isCodeValid')
        ->with($code, equalTo(Secret::fromString($user->otp_secret)))
        ->andReturn(time());

    $otpService = getOtpService();
    $isValid = $otpService->verifyCode($code, $user);

    expect($isValid)
        ->toBeTrue();
});

it('can not validate an invalid code', function (): void {
    $invalidCode = Code::fromString(fake()->word());
    $user = User::factory()
        ->withValidOtpRegistration()
        ->create();

    $this->mock(OneTimePassword::class)
        ->shouldReceive('isCodeValid')
        ->with($invalidCode, equalTo(Secret::fromString($user->otp_secret)))
        ->andReturn(false);

    $otpService = getOtpService();
    $isValid = $otpService->verifyCode($invalidCode, $user);

    expect($isValid)
        ->toBeFalse();
});

it('can not validate if no two-factor secret', function (): void {
    $invalidCode = Code::fromString(fake()->word());
    $user = User::factory()
        ->create([
            'otp_secret' => null,
        ]);

    $otpService = getOtpService();
    $isValid = $otpService->verifyCode($invalidCode, $user);

    expect($isValid)
        ->toBeFalse();
});

function getOtpService(): OtpService
{
    return App::make(OtpService::class);
}
