<?php

declare(strict_types=1);

namespace App\Repositories;

interface AdminLogRepository
{
    /**
     * @param array<array-key, string> $context
     */
    public function log(string $message, array $context = []): void;

    /**
     * @param array<array-key, string> $context
     */
    public function timedLog(callable $action, string $message, array $context = []): void;
}
