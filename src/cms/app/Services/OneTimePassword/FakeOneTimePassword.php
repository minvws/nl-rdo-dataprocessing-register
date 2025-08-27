<?php

declare(strict_types=1);

namespace App\Services\OneTimePassword;

use App\ValueObjects\OneTimePassword\Code;
use App\ValueObjects\OneTimePassword\Secret;

class FakeOneTimePassword extends TimedOneTimePassword
{
    public function isCodeValid(Code $code, Secret $secret): bool
    {
        return true;
    }
}
