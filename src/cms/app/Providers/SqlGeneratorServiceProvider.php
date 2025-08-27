<?php

declare(strict_types=1);

namespace App\Providers;

use App\Console\Commands\SqlGenerate;
use App\Services\SqlExport\Migrator;
use App\Services\SqlExport\SqlFileGenerator;
use Doctrine\SqlFormatter\NullHighlighter;
use Doctrine\SqlFormatter\SqlFormatter;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Webmozart\Assert\Assert;

class SqlGeneratorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Migrator::class, static function (Application $app) {
            $migrationRepository = $app->get('migration.repository');
            Assert::isInstanceOf($migrationRepository, MigrationRepositoryInterface::class);

            $db = $app->get(ConnectionResolverInterface::class);
            $files = $app->get(Filesystem::class);
            $events = $app->get(Dispatcher::class);

            return new Migrator($migrationRepository, $db, $files, $events);
        });

        $this->app->singleton(SqlGenerate::class, static function (Application $app) {
            $migrator = $app->get(Migrator::class);
            $dispatcher = $app->get(Dispatcher::class);

            return new SqlGenerate($migrator, $dispatcher);
        });

        $this->app->when(SqlFileGenerator::class)
            ->needs('$filesystemDisk')
            ->giveConfig('sql-generator.filesystem_disk');
        $this->app->when(SqlFileGenerator::class)
            ->needs('$filesystemManagementDisk')
            ->giveConfig('sql-generator.filesystem_management_disk');
        $this->app->when(SqlFileGenerator::class)
            ->needs('$migrationAdminLogStart')
            ->giveConfig('sql-generator.migration_admin_log_start');
        $this->app->when(SqlFileGenerator::class)
            ->needs(SqlFormatter::class)
            ->give(static function (): SqlFormatter {
                return new SqlFormatter(new NullHighlighter());
            });
    }
}
