<?php

declare(strict_types=1);

namespace App\Services\SqlExport;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Doctrine\SqlFormatter\SqlFormatter;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Console\Output\OutputInterface;
use Webmozart\Assert\Assert;

use function collect;
use function count;
use function sprintf;

class SqlFileGenerator
{
    /** @var array<string> $existingSqlFiles */
    private array $existingSqlFiles;

    /** @var array<string> $newSqlFiles */
    private array $newSqlFiles;

    private readonly CarbonInterface $migrationAdminLogStart;
    private readonly Filesystem $sqlGenerationFilesystem;
    private readonly Filesystem $sqlGenerationManagementFilesystem;

    public function __construct(
        string $filesystemDisk,
        string $filesystemManagementDisk,
        private readonly Migrator $migrator,
        private readonly SqlFormatter $sqlFormatter,
        string $migrationAdminLogStart,
    ) {
        $this->sqlGenerationFilesystem = Storage::disk($filesystemDisk);
        $this->sqlGenerationManagementFilesystem = Storage::disk($filesystemManagementDisk);

        $this->existingSqlFiles = $this->sqlGenerationFilesystem->allFiles();
        $this->migrationAdminLogStart = CarbonImmutable::parse($migrationAdminLogStart);
    }

    /**
     * @param array<string> $migrationPaths
     */
    public function generateSqlFilesFromMigrations(
        array $migrationPaths,
        OutputInterface $output,
        string $version,
    ): void {
        /** @var array<string, string> $migrationFiles */
        $migrationFiles = $this->migrator->getMigrationFiles($migrationPaths);

        foreach ($migrationFiles as $migrationFile) {
            $sqlFilename = $this->determineSqlFileName($migrationFile, $version);
            $sqlQueries = $this->getSqlQueriesFromMigrationFile($migrationFile);

            $this->handleAdminLog($migrationFile, $sqlQueries);

            $this->writeSqlFile($sqlFilename, $sqlQueries->implode("\n\n"));
            $output->writeln(sprintf('Generated %s', $sqlFilename));
        }

        if ($this->sqlGenerationFilesystem->exists($version)) {
            $this->sqlGenerationManagementFilesystem->put('.db_requirements', $version);
        }
    }

    private function determineSqlFileName(string $migrationFile, string $version): string
    {
        $outputFilename = Str::of($this->migrator->getMigrationName($migrationFile))
            ->substr(18)
            ->append('.sql');

        foreach ($this->existingSqlFiles as $existingSqlFile) {
            if ($outputFilename->toString() === Str::of(File::basename($existingSqlFile))->substr(5)->toString()) {
                return $existingSqlFile;
            }
        }

        $this->newSqlFiles[] = $outputFilename->toString();
        $newSqlFilePath = $outputFilename->prepend(sprintf('%04d-', count($this->newSqlFiles)))->toString();

        return sprintf('%s/%s', $version, $newSqlFilePath);
    }

    /**
     * @return Collection<array-key, string>
     */
    private function getSqlQueriesFromMigrationFile(string $migrationFile): Collection
    {
        return $this->migrator->getMigratorQueries($migrationFile, 'up')
            ->map(function (string $query): string {
                $queries = collect()->push($this->sqlFormatter->format($query));

                $queryString = Str::of($query);
                if ($queryString->startsWith('create table')) {
                    $table = $queryString->betweenFirst('"', '"')->toString();

                    $queries->push($this->sqlFormatter->format(sprintf('ALTER TABLE "%s" owner TO "dpr";', $table)));
                }

                return $queries->implode("\n\n");
            });
    }

    /**
     * @param Collection<array-key, string> $sqlQueries
     */
    private function handleAdminLog(string $migrationFile, Collection $sqlQueries): void
    {
        $migrationName = $this->migrator->getMigrationName($migrationFile);
        $migrationTimestamp = CarbonImmutable::createFromFormat(
            'Y_m_d_His',
            Str::of($migrationName)->substr(0, 17)->toString(),
        );
        Assert::isInstanceOf($migrationTimestamp, CarbonImmutable::class);

        if ($this->migrationAdminLogStart->isAfter($migrationTimestamp)) {
            return;
        }

        $sqlQueries->push(
            $this->sqlFormatter->format(sprintf(
                'INSERT INTO "admin_log_entries" ("message", "created_at", "updated_at") values (\'Migrated "%s"\', now(), now());',
                $migrationName,
            )),
        );
    }

    private function writeSqlFile(string $filename, string $contents): void
    {
        $this->sqlGenerationFilesystem->put($filename, $contents);
    }
}
