<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Services\DatabaseHealthService;
use App\Services\Virusscanner\Virusscanner;

use function it;

it('always returns a valid response', function (): void {
    $this->get('/health')->assertOk();
});

it('returns the correct result', function (bool $databaseHealth, bool $virusscannerHealth, bool $isHealthy): void {
    $this->mock(DatabaseHealthService::class)
        ->shouldReceive('isHealthy')
        ->once()
        ->andReturn($databaseHealth);
    $this->mock(Virusscanner::class)
        ->shouldReceive('isHealthy')
        ->once()
        ->andReturn($virusscannerHealth);

    $this->get('/health')->assertExactJson([
        'healthy' => $isHealthy,
        'externals' => [
            'database' => $databaseHealth,
            'virusscanner' => $virusscannerHealth,
        ],
    ]);
})->with([
    [true, true, true],
    [false, true, false],
    [true, false, false],
    [false, false, false],
]);
