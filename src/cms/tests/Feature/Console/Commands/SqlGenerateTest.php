<?php

declare(strict_types=1);

use App\Services\SqlExport\SqlFileGenerator;

it('can run the artisan sql-generate command', function (): void {
    $this->mock(SqlFileGenerator::class)
        ->shouldReceive('generateSqlFilesFromMigrations')
        ->once();

    $this->artisan(sprintf('sql-generate %s', fake()->slug(1)))
        ->assertSuccessful();
});
