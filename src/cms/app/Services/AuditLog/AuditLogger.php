<?php

declare(strict_types=1);

namespace App\Services\AuditLog;

use MinVWS\Logging\Laravel\LogService;
use Psr\Log\LoggerInterface;

class AuditLogger
{
    public function __construct(
        private readonly AuditLogEventFactory $auditLogEventFactory,
        private readonly LoggerInterface $logger,
        private readonly LogService $logService,
    ) {
    }

    public function register(AuditEvent $auditEvent): void
    {
        $this->logger->debug('Audit event registered', ['auditEvent' => $auditEvent]);

        $this->logService->log($this->auditLogEventFactory->fromApplicationEvent($auditEvent));
    }
}
