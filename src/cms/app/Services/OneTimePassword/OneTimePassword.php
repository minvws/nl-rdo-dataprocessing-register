<?php

declare(strict_types=1);

namespace App\Services\OneTimePassword;

interface OneTimePassword
{
    public function isCodeValid(string $code, string $secret): bool;

    public function generateSecretKey(): string;

    public function generateQRCodeInline(string $company, string $secret): string;
}
