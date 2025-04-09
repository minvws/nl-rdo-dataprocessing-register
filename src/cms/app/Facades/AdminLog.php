<?php

declare(strict_types=1);

namespace App\Facades;

use App\Repositories\AdminLogRepository;
use Illuminate\Support\Facades\Facade;

/**
 * @see AdminLogRepository
 *
 * @method static void log(string $message, array<mixed> $context = [])
 * @method static void timedLog(callable $action, string $message, array<mixed> $context = [])
 */
class AdminLog extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return AdminLogRepository::class;
    }
}
