<?php

declare(strict_types=1);

namespace Tests\Helpers;

use Illuminate\Support\Facades\Session;

class SessionTestHelper
{
    public static function setOtpValid(): void
    {
        self::setOtpValidValue(true);
    }

    public static function setOtpInvalid(): void
    {
        self::setOtpValidValue(false);
    }

    protected static function setOtpValidValue(bool $value): void
    {
        Session::put('otp_valid', $value);
    }
}
