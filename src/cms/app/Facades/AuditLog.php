<?php

declare(strict_types=1);

namespace App\Facades;

use App\Services\AuditLog\AuditEvent;
use App\Services\AuditLog\AuditLogger;
use Illuminate\Support\Facades\Facade;

/**
 * @see AuditLogger
 *
 * @method static void register(AuditEvent $auditEvent)
 */
class AuditLog extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return AuditLogger::class;
    }
}
