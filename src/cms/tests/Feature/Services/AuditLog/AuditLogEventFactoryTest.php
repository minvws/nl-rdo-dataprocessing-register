<?php

declare(strict_types=1);

use App\Models\User;
use App\Services\AuditLog\AuditLogEventFactory;
use App\Services\AuditLog\User\UserCreatedEvent;
use MinVWS\AuditLogger\Loggers\LogEventInterface;

it('can create an logEvent from an event', function (): void {
    $user = User::factory()->create();

    $auditLogEventFactory = new AuditLogEventFactory();
    $logEvent = $auditLogEventFactory->fromApplicationEvent(new UserCreatedEvent($user));

    expect($logEvent)
        ->toBeInstanceOf(LogEventInterface::class);
});
