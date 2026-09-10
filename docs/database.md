# Database

## Type and version

- PostgreSQL, version 15
- Version 14 is the minimum: the `Database lint` workflow imports all SQL files into `postgres:14`.
- No extensions required, only built-in types.
- Connection settings come from the environment variables listed under **DATABASE** in
  [environment_variables.md](environment_variables.md).

## Schema versioning

- Production is not migrated with `php artisan migrate`.
- Laravel migrations are the source of truth for development and the test suite.
- Per release they are exported with `php artisan sql-generate <version>` to
  `src/cms/database/sql/<version>/`, and the hosting party applies those SQL files.
- [.db_requirements](../.db_requirements) names the schema version a release requires (`v1.14.0`).
  This is the schema version, not the PostgreSQL engine version, and it lags behind the application
  version because not every release changes the schema.
- Procedure: [DEPLOY_PROCEDURE.md](../DEPLOY_PROCEDURE.md). Per-release hosting steps:
  [HOSTING_CHANGELOG.md](../HOSTING_CHANGELOG.md).

## Local development

- `sail up -d` starts PostgreSQL with a named volume for its data directory.
- The schema is built with `php artisan migrate:fresh --seed`, not from the SQL files.
- A second database, `testing`, is available for the test suite. See [README.md](../README.md).

## Generating the SQL files for a release

Run from `src/cms`, on the release branch, with `<version>` like `v1.2.3`:

1. `php artisan db:wipe` to drop all existing tables.
2. `php artisan sql-execute` to rebuild the database from the SQL files already in `database/sql/`.
3. `php artisan sql-generate <version>` to write SQL for every migration that does not have a file
   yet into `database/sql/<version>/`, and to update `.db_requirements`.
4. Verify the result on a clean install and on an upgrade from the previous version, then commit the
   new files.

Only migrations without an existing SQL file are exported, so the version folder contains exactly the
schema changes of that release. The verification steps and the rest of the release flow are in
[DEPLOY_PROCEDURE.md](../DEPLOY_PROCEDURE.md); the instructions the hosting party receives are in
[HOSTING_CHANGELOG.md](../HOSTING_CHANGELOG.md).
