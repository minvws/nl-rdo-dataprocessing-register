## Deployment Procedure

**Make release branch:**

- Make release-branch `release/v1.2.3` from `develop` and do a local checkout
- Generate new SQL files
  - run `composer install`
  - run `php artisan db:wipe` to remove all existing database-tables
  - run `php artisan sql-execute` to build the database using the existing sql-files
  - run `php artisan sql-generate v1.2.3` to generate new sql-files
  - make sure `.db_requirements` contains the correct database(!) version
  - commit the new sql files
- Test if new SQL files generate errors on clean install
  - run `php artisan db:wipe` to remove all existing database-tables again
  - run `php artisan sql-execute` to re-build the database completely (there should be no errors)
  - run `php artisan user:create-admin` to generate a user and test the application
- Test if new SQL files generate errors on previous version
  - checkout previous version `v1.2.2`
  - run `composer install`
  - run `php artisan db:wipe` to remove all existing database-tables again
  - run `php artisan sql-execute` to re-build the database completely (there should be no errors)
  - run `php artisan user:create-admin` to generate a user and organisation
  - run `php artisan app:core-entity-seeder` to seed the database with entities
  - checkout `release/v1.2.3` branch
  - run `composer install`
  - run `php artisan sql-execute v1.2.3`
  - test the application
- Verify that the Hosting Changelog is complete: add missing steps and commit
- Push changes to the release branch
- Make a PR to merge with `main`

**Make release:**

- After PR merge, make a PR to merge `main` back to `develop`
- Publish a new (pre-)release:
  - Create a release via Github and auto-generate release notes
  - Wait for the action to complete and copy link to the artifact

**Deploy release:**

- Send an email to the helpdesk:
  - asking for a deploy of the (pre-)release to the appropriate environment
  - include the link to the artifact
  - include all steps from the Hosting Changelog related to the version

