<?php

declare(strict_types=1);

namespace App\Services\Virusscanner;

interface Virusscanner
{
    public function isHealthy(): bool;

    /**
     * @param resource $stream
     */
    public function isResourceClean($stream): bool;
}
