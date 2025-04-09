<?php

declare(strict_types=1);

use App\Services\SqlExport\SqlFileGenerator;
use Mockery\MockInterface;

it('can run the artisan sql-generate command', function (): void {
    /** @var SqlFileGenerator&MockInterface $sqlFileGenerator */
    $sqlFileGenerator = $this->mock(SqlFileGenerator::class);
    $sqlFileGenerator->shouldReceive('generateSqlFilesFromMigrations');

    $this->artisan(sprintf('sql-generate %s', fake()->slug(1)))
        ->assertSuccessful();
});
