<?php

declare(strict_types=1);

namespace App\Services\OneTimePassword;

use App\ValueObjects\OneTimePassword\Code;
use App\ValueObjects\OneTimePassword\Secret;

interface OneTimePassword
{
    public function isCodeValid(Code $code, Secret $secret): bool;

    public function generateSecretKey(): string;

    public function generateQRCodeInline(string $label, Secret $secret): string;
}
