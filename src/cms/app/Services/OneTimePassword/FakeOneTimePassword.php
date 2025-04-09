<?php

declare(strict_types=1);

namespace App\Services\OneTimePassword;

class FakeOneTimePassword extends TimedOneTimePassword
{
    public function isCodeValid(string $code, string $secret): bool
    {
        return true;
    }
}
