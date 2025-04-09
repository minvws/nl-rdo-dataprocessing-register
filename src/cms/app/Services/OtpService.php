<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Services\OneTimePassword\OneTimePassword;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Session\Session;
use Webmozart\Assert\Assert;

class OtpService
{
    private const SESSION_IDENTIFIER = 'otp_valid';

    public function __construct(
        private readonly OneTimePassword $oneTimePassword,
        private readonly Session $session,
    ) {
    }

    public function generateQRCodeInline(string $secret, string $label): string
    {
        return $this->oneTimePassword->generateQRCodeInline($label, $secret);
    }

    public function verifyCode(string $code, User $user): bool
    {
        $otpSecret = $user->otp_secret;
        Assert::nullOrString($otpSecret);

        if ($otpSecret === null) {
            return false;
        }

        $isValid = $this->oneTimePassword->isCodeValid($code, $otpSecret);

        if ($isValid === false) {
            return false;
        }

        $user->otp_confirmed_at = CarbonImmutable::now();
        $user->save();

        $this->setValidSession();

        return true;
    }

    public function hasValidSession(): bool
    {
        return $this->session->get(self::SESSION_IDENTIFIER, false) === true;
    }

    public function hasOtpEnabled(User $user): bool
    {
        return $user->otp_secret !== null;
    }

    public function hasOtpConfirmed(User $user): bool
    {
        return $user->otp_secret !== null && $user->otp_confirmed_at !== null;
    }

    public function enable(User $user): void
    {
        $user->otp_secret = $this->oneTimePassword->generateSecretKey();
        $user->save();
    }

    public function disable(User $user): void
    {
        $user->otp_secret = null;
        $user->otp_confirmed_at = null;
        $user->save();
    }

    private function setValidSession(): void
    {
        $this->session->put(self::SESSION_IDENTIFIER, true);
    }
}
