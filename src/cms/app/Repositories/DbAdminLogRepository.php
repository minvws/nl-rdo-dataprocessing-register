<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\AdminLogEntry;
use Psr\Log\LoggerInterface;
use Throwable;

use function microtime;
use function round;

class DbAdminLogRepository implements AdminLogRepository
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * @param array<int|string, string> $context
     */
    public function log(string $message, array $context = []): void
    {
        $this->logger->debug($message, $context);

        $entry = new AdminLogEntry([
            'message' => $message,
            'context' => $context,
        ]);
        $entry->save();
    }

    /**
     * @param array<int|string, string> $context
     *
     * @throws Throwable
     */
    public function timedLog(callable $action, string $message, array $context = []): void
    {
        $start = microtime(true);

        try {
            $action();
        } catch (Throwable $exception) {
            $context['exception'] = $exception->getMessage();

            throw $exception;
        } finally {
            $this->log(
                $message,
                $context + ['duration_seconds' => (string) round(microtime(true) - $start, 4)],
            );
        }
    }
}
