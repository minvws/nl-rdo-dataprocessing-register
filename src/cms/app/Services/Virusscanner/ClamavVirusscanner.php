<?php

declare(strict_types=1);

namespace App\Services\Virusscanner;

use App\Config\Config;
use Socket\Raw\Factory;
use Xenolope\Quahog\Client;
use Xenolope\Quahog\Exception\ConnectionException;

use const PHP_NORMAL_READ;

/**
 * @codeCoverageIgnore
 * Implementation can only be tested if clamav is actually available (not desirable on CI)
 */
class ClamavVirusscanner implements Virusscanner
{
    public function isHealthy(): bool
    {
        try {
            return $this->getClient()->ping();
        } catch (ConnectionException) {
            return false;
        }
    }

    /**
     * @param resource $stream
     */
    public function isResourceClean($stream): bool
    {
        return $this->getClient()
            ->scanResourceStream($stream)
            ->isOk();
    }

    private function getClient(): Client
    {
        return new Client(
            (new Factory())->createClient(Config::string('virusscanner.clamav.socket')),
            Config::integer('virusscanner.clamav.socket_read_timeout'),
            PHP_NORMAL_READ,
        );
    }
}
