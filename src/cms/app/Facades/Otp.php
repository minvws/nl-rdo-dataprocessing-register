<?php

declare(strict_types=1);

namespace App\Facades;

use App\Models\User;
use App\Services\OtpService;
use App\ValueObjects\OneTimePassword\Code;
use App\ValueObjects\OneTimePassword\Secret;
use Illuminate\Support\Facades\Facade;

/**
 * @see OtpService
 *
 * @method static string generateQRCodeInline(Secret $secret, string $label)
 * @method static void disable(User $user)
 * @method static void enable(User $user)
 * @method static bool hasOtpConfirmed(User $user)
 * @method static bool hasOtpEnabled(User $user)
 * @method static bool hasValidSession()
 * @method static bool verifyCode(Code $code, User $user)
 */
class Otp extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return OtpService::class;
    }
}
