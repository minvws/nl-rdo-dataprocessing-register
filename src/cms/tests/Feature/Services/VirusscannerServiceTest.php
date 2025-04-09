<?php

declare(strict_types=1);

use App\Services\VirusscannerService;
use Tests\Helpers\ConfigHelper;

it('scanResource forwards call to client', function (): void {
    /** @var resource $stream */
    $stream = fake()->word();

    ConfigHelper::set('virusscanner.default', 'fake');

    /** @var VirusscannerService $virusscannerService */
    $virusscannerService = $this->app->get(VirusscannerService::class);

    $result = $virusscannerService->isResourceClean($stream);

    expect($result)
        ->toBeTrue();
});
