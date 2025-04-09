<?php

declare(strict_types=1);

namespace App\Services;

use App\Config\Config;
use App\Services\Virusscanner\Virusscanner;
use App\Services\Virusscanner\VirusscannerManager;
use Webmozart\Assert\Assert;

class VirusscannerService
{
    public function __construct(
        private readonly VirusscannerManager $virusscannerManager,
    ) {
    }

    /**
     * @param resource $stream
     */
    public function isResourceClean($stream): bool
    {
        $client = $this->getClient();

        return $client->isResourceClean($stream);
    }

    private function getClient(): Virusscanner
    {
        $virusscanner = $this->virusscannerManager->driver(Config::string('virusscanner.default'));
        Assert::isInstanceOf($virusscanner, Virusscanner::class);

        return $virusscanner;
    }
}
