<?php

declare(strict_types=1);

use App\Services\SqlExport\Migrator;
use App\Services\SqlExport\SqlFileGenerator;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Storage;
use Mockery\MockInterface;
use Symfony\Component\Console\Output\NullOutput;
use Tests\Helpers\ConfigHelper;

it('outputs to default folder', function (): void {
    $sqlGenerationFilesystem = Storage::fake(ConfigHelper::get('sql-generator.filesystem_disk'));
    $sqlGenerationManagementFilesystem = Storage::fake(ConfigHelper::get('sql-generator.filesystem_management_disk'));

    $version = fake()->slug(1);
    $migrationName = fake()->slug();
    // using e.g. fake()->word() sometimes generates sql-keywords that will be capitalized (and therefore differs from the expected outout)
    $migrationContent = fake()->randomElement(['foo', 'bar', 'baz']);

    /** @var Migrator&MockInterface $migrator */
    $migrator = $this->mock(Migrator::class);
    $migrator->shouldReceive([
        'getMigrationFiles' => [$migrationName => $migrationName],
        'getMigrationName' => sprintf('%s_%s', now()->format('Y_m_d_His'), $migrationName),
        'getMigratorQueries' => collect([$migrationContent]),
    ]);

    /** @var SqlFileGenerator $sqlFileGenerator */
    $sqlFileGenerator = $this->app->make(SqlFileGenerator::class, [
        'sqlGenerationFilesystem' => $sqlGenerationFilesystem,
        'sqlGenerationManagementFilesystem' => $sqlGenerationManagementFilesystem,
    ]);
    $sqlFileGenerator->generateSqlFilesFromMigrations(
        [],
        new NullOutput(),
        $version,
    );

    $fileContents = $sqlGenerationFilesystem->get(sprintf('%s/0001-%s.sql', $version, $migrationName));
    expect($fileContents)
        ->toStartWith($migrationContent);
});

it('regenerates existing files', function (): void {
    $sqlGenerationFilesystem = Storage::fake(ConfigHelper::get('sql-generator.filesystem_disk'));
    $sqlGenerationManagementFilesystem = Storage::fake(ConfigHelper::get('sql-generator.filesystem_management_disk'));

    $version = fake()->slug(1);
    $migrationName = fake()->slug();
    // using e.g. fake()->word() sometimes generates sql-keywords that will be capitalized (and therefore differs from the expected outout)
    $migrationContent = fake()->randomElement(['foo', 'bar', 'baz']);

    // create file with random contents
    $sqlGenerationFilesystem->put(sprintf('%s/0001-%s.sql', $version, $migrationName), fake()->sentence());

    /** @var Migrator&MockInterface $migrator */
    $migrator = $this->mock(Migrator::class);
    $migrator->shouldReceive([
        'getMigrationFiles' => [$migrationName => $migrationContent],
        'getMigrationName' => sprintf('%s_%s', now()->format('Y_m_d_His'), $migrationName),
        'getMigratorQueries' => collect([$migrationContent]),
    ]);

    /** @var SqlFileGenerator $sqlFileGenerator */
    $sqlFileGenerator = $this->app->make(SqlFileGenerator::class, [
        'sqlGenerationFilesystem' => $sqlGenerationFilesystem,
        'sqlGenerationManagementFilesystem' => $sqlGenerationManagementFilesystem,
    ]);
    $sqlFileGenerator->generateSqlFilesFromMigrations(
        [],
        new NullOutput(),
        $version,
    );

    $fileContents = $sqlGenerationFilesystem->get(sprintf('%s/0001-%s.sql', $version, $migrationName));
    expect($fileContents)
        ->toStartWith($migrationContent);
});

it('generates line to set owner on create table', function (): void {
    $sqlGenerationFilesystem = Storage::fake(ConfigHelper::get('sql-generator.filesystem_disk'));
    $sqlGenerationManagementFilesystem = Storage::fake(ConfigHelper::get('sql-generator.filesystem_management_disk'));

    $version = fake()->slug(1);
    $migrationName = fake()->slug();
    $table = fake()->slug(1);
    $migrationContent = sprintf('create table "%s" ... and some other sql', $table);

    // create file with random contents
    $sqlGenerationFilesystem->put(sprintf('%s/0001-%s.sql', $version, $migrationName), fake()->sentence());

    /** @var Migrator&MockInterface $migrator */
    $migrator = $this->mock(Migrator::class);
    $migrator->shouldReceive([
        'getMigrationFiles' => [$migrationName => $migrationContent],
        'getMigrationName' => sprintf('%s_%s', now()->format('Y_m_d_His'), $migrationName),
        'getMigratorQueries' => collect([$migrationContent]),
    ]);

    /** @var SqlFileGenerator $sqlFileGenerator */
    $sqlFileGenerator = $this->app->make(SqlFileGenerator::class, [
        'sqlGenerationFilesystem' => $sqlGenerationFilesystem,
        'sqlGenerationManagementFilesystem' => $sqlGenerationManagementFilesystem,
    ]);
    $sqlFileGenerator->generateSqlFilesFromMigrations(
        [],
        new NullOutput(),
        $version,
    );

    $fileContents = $sqlGenerationFilesystem->get(sprintf('%s/0001-%s.sql', $version, $migrationName));
    expect($fileContents)
        ->toContain(sprintf('"%s" owner TO "dpr";', $table));
});

it('appends an insert query to log the executed migration in admin_log_entries', function (): void {
    $sqlGenerationFilesystem = Storage::fake(ConfigHelper::get('sql-generator.filesystem_disk'));
    $sqlGenerationManagementFilesystem = Storage::fake(ConfigHelper::get('sql-generator.filesystem_management_disk'));

    ConfigHelper::set('sql-generator.migration_admin_log_start', now()->subHour()->toDateTimeString());
    $migrationName = fake()->slug();
    $migrationFullName = sprintf('%s_%s', now()->format('Y_m_d_His'), $migrationName);
    $migrationContent = fake()->sentence();
    $version = fake()->slug(1);

    /** @var Migrator&MockInterface $migrator */
    $migrator = $this->mock(Migrator::class);
    $migrator->shouldReceive([
        'getMigrationFiles' => [$migrationName => $migrationContent],
        'getMigrationName' => $migrationFullName,
        'getMigratorQueries' => collect([$migrationContent]),
    ]);

    /** @var SqlFileGenerator $sqlFileGenerator */
    $sqlFileGenerator = $this->app->make(SqlFileGenerator::class, [
        'sqlGenerationFilesystem' => $sqlGenerationFilesystem,
        'sqlGenerationManagementFilesystem' => $sqlGenerationManagementFilesystem,
    ]);
    $sqlFileGenerator->generateSqlFilesFromMigrations(
        [],
        new NullOutput(),
        $version,
    );

    $sqlFile = $sqlGenerationFilesystem->get(sprintf('%s/0001-%s.sql', $version, $migrationName));

    expect($sqlFile)
        ->toContain('INSERT INTO "admin_log_entries" (', sprintf('Migrated "%s"', $migrationFullName));
});

it('does not append an admin log insert statement if the migration is older than the configured start date', function (): void {
    $sqlGenerationFilesystem = Storage::fake(ConfigHelper::get('sql-generator.filesystem_disk'));
    $sqlGenerationManagementFilesystem = Storage::fake(ConfigHelper::get('sql-generator.filesystem_management_disk'));

    ConfigHelper::set('sql-generator.migration_admin_log_start', now()->addHour()->toDateTimeString());
    $migrationName = fake()->slug();
    $migrationFullName = sprintf('%s_%s', now()->format('Y_m_d_His'), $migrationName);
    $migrationContent = fake()->sentence();
    $version = fake()->slug(1);

    /** @var Migrator&MockInterface $migrator */
    $migrator = $this->mock(Migrator::class);
    $migrator->shouldReceive([
        'getMigrationFiles' => [$migrationName => $migrationContent],
        'getMigrationName' => $migrationFullName,
        'getMigratorQueries' => collect([$migrationContent]),
    ]);

    /** @var SqlFileGenerator $sqlFileGenerator */
    $sqlFileGenerator = $this->app->make(SqlFileGenerator::class, [
        'sqlGenerationFilesystem' => $sqlGenerationFilesystem,
        'sqlGenerationManagementFilesystem' => $sqlGenerationManagementFilesystem,
    ]);
    $sqlFileGenerator->generateSqlFilesFromMigrations(
        [],
        new NullOutput(),
        $version,
    );

    $sqlFile = $sqlGenerationFilesystem->get(sprintf('%s/0001-%s.sql', $version, $migrationName));

    expect($sqlFile)
        ->not->toContain('insert into "admin_log_entries" (');
});

it('generates the db-requirements file with the correct contents if there are new migrations', function (): void {
    $sqlGenerationFilesystem = Storage::fake(ConfigHelper::get('sql-generator.filesystem_disk'));
    $sqlGenerationManagementFilesystem = Storage::fake(ConfigHelper::get('sql-generator.filesystem_management_disk'));

    $migrationName = sprintf('%s_%s.sql', CarbonImmutable::now()->format('Y_m_d_His'), fake()->slug());
    $migrationContent = fake()->sentence();

    /** @var Migrator&MockInterface $migrator */
    $migrator = $this->mock(Migrator::class);
    $migrator->shouldReceive([
        'getMigrationFiles' => [$migrationName => $migrationContent],
        'getMigrationName' => $migrationName,
        'getMigratorQueries' => collect([$migrationContent]),
    ]);

    $newVersion = fake()->unique()->slug(1);
    /** @var SqlFileGenerator $sqlFileGenerator */
    $sqlFileGenerator = $this->app->make(SqlFileGenerator::class, [
        'sqlGenerationFilesystem' => $sqlGenerationFilesystem,
        'sqlGenerationManagementFilesystem' => $sqlGenerationManagementFilesystem,
    ]);
    $sqlFileGenerator->generateSqlFilesFromMigrations(
        [],
        new NullOutput(),
        $newVersion,
    );

    $newFileContents = $sqlGenerationManagementFilesystem->get('.db_requirements');
    expect($newFileContents)
        ->toBe($newVersion);
});
