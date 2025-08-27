<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Config\Config;
use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webmozart\Assert\Assert;

use function sprintf;
use function uksort;
use function version_compare;

class SqlExecute extends Command
{
    protected $signature = 'sql-execute {version?}';
    protected $description = 'Execute sql-files instead of laravel migrations';

    public function handle(DatabaseManager $databaseManager): int
    {
        $version = $this->argument('version');
        Assert::nullOrStringNotEmpty($version);

        $fileSystem = Storage::disk(Config::string('sql-generator.filesystem_disk'));
        $allFiles = $fileSystem->allFiles($version);
        Assert::allString($allFiles);
        $versionedFiles = $this->getVersionedFiles($allFiles);

        foreach ($versionedFiles as $files) {
            foreach ($files as $file) {
                $query = $fileSystem->get($file);
                Assert::string($query);

                $databaseManager->unprepared($query);
                $this->output->writeln(sprintf('Executed %s', $file));
            }
        }

        $this->output->success('Sql-execution done');

        return self::SUCCESS;
    }

    /**
     * @param array<string> $files
     *
     * @return array<string, array<int, string>>
     */
    private function getVersionedFiles(array $files): array
    {
        $versionedFiles = [];

        foreach ($files as $file) {
            $version = Str::of($file)->before('/')->toString();
            $versionedFiles[$version][] = $file;
        }

        uksort($versionedFiles, static function (string $a, string $b): int {
            return version_compare($a, $b);
        });

        return $versionedFiles;
    }
}
