<?php

declare(strict_types=1);

namespace App\Providers;

use App\Console\Commands\SqlGenerate;
use App\Services\SqlExport\Migrator;
use App\Services\SqlExport\SqlFileGenerator;
use Doctrine\SqlFormatter\NullHighlighter;
use Doctrine\SqlFormatter\SqlFormatter;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;

class SqlGeneratorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Migrator::class, static function ($app) {
            return new Migrator(
                $app['migration.repository'],
                $app['db'],
                $app['files'],
                $app['events'],
            );
        });

        $this->app->singleton(SqlGenerate::class, static function ($app) {
            return new SqlGenerate($app['migrator'], $app[Dispatcher::class]);
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
