<?php

declare(strict_types=1);

namespace App\Services\AuditLog\User;

use App\Models\User;
use App\Services\AuditLog\AuditEvent;

class UserUpdatedEvent implements AuditEvent
{
    public function __construct(
        public readonly User $user,
    ) {
    }
}
