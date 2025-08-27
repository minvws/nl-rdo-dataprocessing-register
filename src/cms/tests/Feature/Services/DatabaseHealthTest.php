<?php

declare(strict_types=1);

use App\Services\DatabaseHealthService;
use Illuminate\Database\DatabaseManager;

it('will return the correct state', function (bool $isHealthy): void {
    $this->mock(DatabaseManager::class)
        ->shouldReceive('statement')
        ->once()
        ->with('SELECT 1')
        ->andReturn($isHealthy);

    $databaseHealthService = $this->app->get(DatabaseHealthService::class);
    expect($databaseHealthService->isHealthy())
        ->toBe($isHealthy);
})->with([
    [true],
    [false],
]);
