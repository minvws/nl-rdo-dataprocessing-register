<?php

declare(strict_types=1);

namespace App\Services\Virusscanner;

class FakeVirusscanner implements Virusscanner
{
    /**
     * @param resource $stream
     */
    public function isResourceClean($stream): bool
    {
        return true;
    }
}
