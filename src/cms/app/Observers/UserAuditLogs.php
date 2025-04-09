<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\User;
use App\Services\AuditLog\AuditLogger;
use App\Services\AuditLog\User\UserCreatedEvent;
use App\Services\AuditLog\User\UserUpdatedEvent;

class UserAuditLogs
{
    public function __construct(
        private readonly AuditLogger $auditLogger,
    ) {
    }

    public function created(User $user): void
    {
        $this->auditLogger->register(new UserCreatedEvent($user));
    }

    public function updated(User $user): void
    {
        $this->auditLogger->register(new UserUpdatedEvent($user));
    }
}
