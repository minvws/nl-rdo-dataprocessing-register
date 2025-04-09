<?php

declare(strict_types=1);

namespace App\Services\Virusscanner;

use App\Config\Config;
use Illuminate\Support\Manager;

class VirusscannerManager extends Manager
{
    public function createClamavDriver(): ClamavVirusscanner
    {
        /** @var ClamavVirusscanner $clamavVirusscanner */
        $clamavVirusscanner = $this->container->get(ClamavVirusscanner::class);

        return $clamavVirusscanner;
    }

    public function createFakeDriver(): FakeVirusscanner
    {
        /** @var FakeVirusscanner $fakeVirusscanner */
        $fakeVirusscanner = $this->container->get(FakeVirusscanner::class);

        return $fakeVirusscanner;
    }

    public function getDefaultDriver(): string
    {
        return Config::string('virusscanner.default');
    }
}
