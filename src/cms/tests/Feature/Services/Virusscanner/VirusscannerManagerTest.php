<?php

declare(strict_types=1);

use App\Services\Virusscanner\ClamavVirusscanner;
use App\Services\Virusscanner\FakeVirusscanner;
use App\Services\Virusscanner\VirusscannerManager;
use Tests\Helpers\ConfigHelper;

it('returns fake instance', function (): void {
    ConfigHelper::set('virusscanner.default', 'fake');

    /** @var VirusscannerManager $virusscannerManager */
    $virusscannerManager = $this->app->get(VirusscannerManager::class);
    $virusscanner = $virusscannerManager->driver();

    expect($virusscanner)
        ->toBeInstanceOf(FakeVirusscanner::class);
});

it('returns clamav instance', function (): void {
    ConfigHelper::set('virusscanner.default', 'clamav');

    /** @var VirusscannerManager $virusscannerManager */
    $virusscannerManager = $this->app->get(VirusscannerManager::class);
    $virusscanner = $virusscannerManager->driver();

    expect($virusscanner)
        ->toBeInstanceOf(ClamavVirusscanner::class);
});
