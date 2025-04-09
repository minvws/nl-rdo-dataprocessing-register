<?php

declare(strict_types=1);

namespace App\Services\OneTimePassword;

use App\Config\Config;
use Illuminate\Support\Manager;

class OneTimePasswordManager extends Manager
{
    public function createTimedDriver(): TimedOneTimePassword
    {
        /** @var TimedOneTimePassword $timedOneTimePassword */
        $timedOneTimePassword = $this->container->get(TimedOneTimePassword::class);

        return $timedOneTimePassword;
    }

    public function createFakeDriver(): FakeOneTimePassword
    {
        /** @var FakeOneTimePassword $fakeOneTimePassword */
        $fakeOneTimePassword = $this->container->get(FakeOneTimePassword::class);

        return $fakeOneTimePassword;
    }

    public function getDefaultDriver(): string
    {
        return Config::string('auth.one_time_password.driver');
    }
}
