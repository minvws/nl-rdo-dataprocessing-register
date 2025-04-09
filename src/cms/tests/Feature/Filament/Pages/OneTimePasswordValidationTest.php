<?php

declare(strict_types=1);

use App\Filament\Pages\OneTimePasswordValidation;
use App\Services\OtpService;
use Illuminate\Support\Facades\RateLimiter;
use Tests\Helpers\ConfigHelper;

use function Pest\Livewire\livewire;

it('loads the page', function (): void {
    setOtpValidSessionValue(false);

    $this->get(sprintf('%s/two-factor-authentication', $this->organisation->slug))
        ->assertSee(__('user.one_time_password.description'));
});

it('redirects user to home on valid session', function (): void {
    setOtpValidSessionValue(true);

    $this->get(sprintf('%s/two-factor-authentication', $this->organisation->slug))
        ->assertRedirect(sprintf('%s/avg-responsible-processing-records', $this->organisation->slug));
});

it('redirects with a valid code', function (): void {
    setOtpValidSessionValue(true);

    $this->mock(OtpService::class, static function ($mock): void {
        $mock->shouldReceive('hasValidSession')
            ->andReturn(false);
        $mock->shouldReceive('verifyCode')
            ->andReturn(true);
    });

    livewire(OneTimePasswordValidation::class)
        ->fillForm([
            'code' => fake()->unique()->randomNumber(6),
        ])
        ->call('authenticate')
        ->assertHasNoFormErrors()
        ->assertRedirect(sprintf('%s/avg-responsible-processing-records', $this->organisation->slug));
});

it('fails with a invalid code', function (): void {
    setOtpValidSessionValue(false);

    livewire(OneTimePasswordValidation::class)
        ->fillForm([
            'code' => fake()->unique()->randomNumber(6),
        ])
        ->call('authenticate')
        ->assertHasFormErrors(['code' => __('user.profile.one_time_password.confirmation.invalid_code')]);
});

it('fails if rate limit reached', function (): void {
    setOtpValidSessionValue(false);
    ConfigHelper::set('auth.one_time_password.validation_rate_limit.max_attempts', 1);
    ConfigHelper::set('auth.one_time_password.validation_rate_limit.decay_in_seconds', 60);

    RateLimiter::shouldReceive('availableIn')
        ->once()
        ->andReturn(60);
    RateLimiter::shouldReceive('tooManyAttempts')
        ->once()
        ->andReturn(true);

    livewire(OneTimePasswordValidation::class)
        ->fillForm([
            'code' => fake()->unique()->randomNumber(6),
        ])
        ->call('authenticate')
        ->assertHasFormErrors(['code' => __('user.profile.one_time_password.confirmation.too_many_requests', ['seconds' => 60])]);
});
