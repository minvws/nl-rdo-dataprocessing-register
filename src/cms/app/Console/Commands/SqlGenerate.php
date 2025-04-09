<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\SqlExport\SqlFileGenerator;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Console\Migrations\BaseCommand;
use Illuminate\Database\Migrations\Migrator;

class SqlGenerate extends BaseCommand
{
    protected $signature = 'sql-generate {version}';
    protected $description = 'Generate sql-files for the laravel migrations';

    public function __construct(
        protected readonly Migrator $migrator,
        protected readonly Dispatcher $dispatcher,
    ) {
        parent::__construct();
    }

    public function handle(SqlFileGenerator $sqlFileGenerator): int
    {
        $version = $this->argument('version');

        $sqlFileGenerator->generateSqlFilesFromMigrations(
            $this->getMigrationPaths(),
            $this->output,
            $version,
        );

        $this->output->success('Sql-export done');

        return self::SUCCESS;
    }
}
