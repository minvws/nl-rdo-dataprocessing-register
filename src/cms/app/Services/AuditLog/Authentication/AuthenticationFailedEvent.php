<?php

declare(strict_types=1);

namespace App\Services\AuditLog\Authentication;

use App\Services\AuditLog\AuditEvent;

class AuthenticationFailedEvent implements AuditEvent
{
    public const INVALID_TOKEN = 'InvalidToken';
    public const NO_TOKEN_FOUND = 'NoTokenFound';
    public const NO_ORGANISATION_FOUND = 'NoOrganisationFound';

    private function __construct(public readonly string $type)
    {
    }

    public static function invalidToken(): self
    {
        return new self(self::INVALID_TOKEN);
    }

    public static function noTokenFound(): self
    {
        return new self(self::NO_TOKEN_FOUND);
    }

    public static function noOrganisationFound(): self
    {
        return new self(self::NO_ORGANISATION_FOUND);
    }
}
