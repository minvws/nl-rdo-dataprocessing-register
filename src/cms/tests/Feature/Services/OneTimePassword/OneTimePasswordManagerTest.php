<?php

declare(strict_types=1);

use App\Services\OneTimePassword\FakeOneTimePassword;
use App\Services\OneTimePassword\OneTimePasswordManager;
use App\Services\OneTimePassword\TimedOneTimePassword;
use Illuminate\Support\Facades\App;
use Tests\Helpers\ConfigTestHelper;

it('returns fake instance', function (): void {
    ConfigTestHelper::set('auth.one_time_password.driver', 'fake');

    $oneTimePasswordManager = App::make(OneTimePasswordManager::class);
    expect($oneTimePasswordManager->driver())
        ->toBeInstanceOf(FakeOneTimePassword::class);
});

it('returns timed instance', function (): void {
    ConfigTestHelper::set('auth.one_time_password.driver', 'timed');

    $oneTimePasswordManager = App::make(OneTimePasswordManager::class);
    expect($oneTimePasswordManager->driver())
        ->toBeInstanceOf(TimedOneTimePassword::class);
});
