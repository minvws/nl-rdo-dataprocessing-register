<?php

declare(strict_types=1);

use App\Livewire\User\Profile\OneTimePassword;
use App\Services\OtpService;
use Mockery\MockInterface;

use function Pest\Livewire\livewire;

it('can mount the component', function (): void {
    livewire(OneTimePassword::class, [
        'user' => $this->user,
    ])->assertSee(__('user.profile.one_time_password.title'));
});

it('can enable', function (): void {
    livewire(OneTimePassword::class, [
        'user' => $this->user,
    ])
        ->callAction('enable')
        ->assertNotified(__('user.profile.one_time_password.enabled.notify'));
});

it('can disable', function (): void {
    livewire(OneTimePassword::class, [
        'user' => $this->user,
    ])
        ->callAction('disable')
        ->assertNotified(__('user.profile.one_time_password.disabling.notify'));
});

it('can confirm', function (): void {
    $user = $this->user;

    $this->mock(OtpService::class, static function (MockInterface $mock): void {
        $mock->shouldReceive('hasOtpEnabled')->andReturn(true);
        $mock->shouldReceive('hasOtpConfirmed')->andReturn(true);
        $mock->shouldReceive('verifyCode')->andReturn(true);
    });

    livewire(OneTimePassword::class, [
        'user' => $user,
    ])
        ->callAction('confirm', [
            'code' => fake()->word(),
        ])
        ->assertNotified(__('user.profile.one_time_password.confirmation.success_notification'));
});

it('cannot confirm with an invalid code', function (): void {
    $user = $this->user;

    $this->mock(OtpService::class, static function (MockInterface $mock): void {
        $mock->shouldReceive('hasOtpEnabled')->andReturn(true);
        $mock->shouldReceive('hasOtpConfirmed')->andReturn(true);
        $mock->shouldReceive('verifyCode')->andReturn(false);
    });

    livewire(OneTimePassword::class, [
        'user' => $user,
    ])
        ->callAction('confirm', [
            'code' => fake()->word(),
        ])
        ->assertNotNotified(__('user.profile.one_time_password.confirmation.success_notification'));
});

it('can regenerate codes', function (): void {
    $this->mock(OtpService::class, static function (MockInterface $mock): void {
        $mock->shouldReceive('hasOtpEnabled')->andReturn(true);
        $mock->shouldReceive('hasOtpConfirmed')->andReturn(false);
        $mock->shouldReceive('generateQRCOdeInline')->andReturn('<img>');
        $mock->shouldReceive('disable');
        $mock->shouldReceive('enable');
    });

    livewire(OneTimePassword::class, [
        'user' => $this->user,
    ])
        ->callAction('regenerateCodes')
        ->assertNotified(__('user.profile.one_time_password.regenerate_codes.notify'));
});

it('can get the qr code', function (): void {
    $image = fake()->uuid();

    $this->mock(OtpService::class, static function (MockInterface $mock) use ($image): void {
        $mock->shouldReceive('hasOtpEnabled')->andReturn(true);
        $mock->shouldReceive('hasOtpConfirmed')->andReturn(true);
        $mock->shouldReceive('generateQRCodeInline')->andReturn($image);
    });

    livewire(OneTimePassword::class, [
        'user' => $this->user,
    ])
        ->call('getQrCode')
        ->assertReturned($image);
});
