<?php

declare(strict_types=1);

namespace App\Services\AuditLog;

use App\Services\AuditLog\Authentication\AuthenticationFailedEvent;
use App\Services\AuditLog\Authentication\AuthenticationSuccessEvent;
use App\Services\AuditLog\User\UserCreatedEvent;
use App\Services\AuditLog\User\UserPermissionUpdatedEvent;
use App\Services\AuditLog\User\UserTwoFactorResetEvent;
use App\Services\AuditLog\User\UserUpdatedEvent;
use InvalidArgumentException;
use MinVWS\AuditLogger\Events\Logging\AccountChangeLogEvent;
use MinVWS\AuditLogger\Events\Logging\GeneralLogEvent;
use MinVWS\AuditLogger\Events\Logging\UserCreatedLogEvent;
use MinVWS\AuditLogger\Events\Logging\UserLoginLogEvent;
use MinVWS\AuditLogger\Loggers\LogEventInterface;

class AuditLogEventFactory
{
    public function fromApplicationEvent(AuditEvent $event): LogEventInterface
    {
        return match ($event::class) {
            AuthenticationFailedEvent::class => $this->fromAuthenticationFailedEvent($event),
            AuthenticationSuccessEvent::class => $this->fromAuthenticationSuccessEvent($event),
            UserCreatedEvent::class => $this->fromUserCreatedEvent($event),
            UserPermissionUpdatedEvent::class => $this->fromUserPermissionUpdatedEvent($event),
            UserTwoFactorResetEvent::class => $this->fromUserTwoFactorResetEvent($event),
            UserUpdatedEvent::class => $this->fromUserUpdatedEvent($event),
            default => throw new InvalidArgumentException('Unknown audit event'),
        };
    }

    private function fromAuthenticationFailedEvent(AuthenticationFailedEvent $event): GeneralLogEvent
    {
        $code = match ($event->type) {
            AuthenticationFailedEvent::INVALID_TOKEN => '093334',
            AuthenticationFailedEvent::NO_TOKEN_FOUND => '093335',
            AuthenticationFailedEvent::NO_ORGANISATION_FOUND => '093336',
            // @codeCoverageIgnoreStart
            default => throw new InvalidArgumentException('Unknown type'),
            // @codeCoverageIgnoreEnd
        };

        $minvwsAuditLogEvent = new class extends GeneralLogEvent {
        };
        $minvwsAuditLogEvent->withEventCode($code);

        return $minvwsAuditLogEvent;
    }

    private function fromAuthenticationSuccessEvent(AuthenticationSuccessEvent $event): UserLoginLogEvent
    {
        $minvwsAuditLogEvent = new UserLoginLogEvent();
        $minvwsAuditLogEvent->withTarget($event->user);

        return $minvwsAuditLogEvent;
    }

    private function fromUserCreatedEvent(UserCreatedEvent $event): UserCreatedLogEvent
    {
        $minvwsAuditLogEvent = new UserCreatedLogEvent();
        $minvwsAuditLogEvent->withTarget($event->user);

        return $minvwsAuditLogEvent;
    }

    private function fromUserPermissionUpdatedEvent(UserPermissionUpdatedEvent $event): AccountChangeLogEvent
    {
        $minvwsAuditLogEvent = new AccountChangeLogEvent();
        $minvwsAuditLogEvent->withEventCode(AccountChangeLogEvent::EVENTCODE_ROLES);
        $minvwsAuditLogEvent->withTarget($event->user);

        return $minvwsAuditLogEvent;
    }

    private function fromUserTwoFactorResetEvent(UserTwoFactorResetEvent $event): AccountChangeLogEvent
    {
        $minvwsAuditLogEvent = new AccountChangeLogEvent();
        $minvwsAuditLogEvent->withEventCode(AccountChangeLogEvent::EVENTCODE_RESET);
        $minvwsAuditLogEvent->withTarget($event->user);

        return $minvwsAuditLogEvent;
    }

    private function fromUserUpdatedEvent(UserUpdatedEvent $event): AccountChangeLogEvent
    {
        $minvwsAuditLogEvent = new AccountChangeLogEvent();
        $minvwsAuditLogEvent->withTarget($event->user);

        return $minvwsAuditLogEvent;
    }
}
