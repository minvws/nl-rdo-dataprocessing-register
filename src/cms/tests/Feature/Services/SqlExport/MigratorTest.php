<?php

declare(strict_types=1);

use App\Services\SqlExport\Migrator;

it('can get migrator queries', function (): void {
    /** @var Migrator $migrator */
    $migrator = $this->app->get(Migrator::class);

    $queries = $migrator->getMigratorQueries(database_path('migrations/2019_08_19_000000_create_failed_jobs_table.php'), 'up');

    expect($queries[0])
        ->toBe(
            'create table "failed_jobs" ("id" uuid not null, "connection" text not null, "queue" text not null, "payload" text not null, "exception" text not null, "failed_at" timestamp(0) without time zone not null default CURRENT_TIMESTAMP);',
        )
        ->and($queries[1])
        ->toBe('alter table "failed_jobs" add primary key ("id");');
});
