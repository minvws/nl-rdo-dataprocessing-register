# Verwerkingsregister

## Introduction

This repository contains the Verwerkingsregister. This project has 2 main components:

**CMS**

The CMS is built using [Laravel](https://laravel.com/). This is where all the data for the processing records (verwerkingen) are kept and maintained.

Directory: `/src/cms/`

**Public website**

This contains the configuration for generating a static website using [Hugo](https://gohugo.io/). It uses JSON and markdown data as its input to generate static html files.

Directory: `/src/public-website/`

## Documentation

- See [docs/environment_variables.md](docs/environment_variables.md) for an overview of all environment variables that can be set in the `.env` file.
- See [docs/roles_and_permissions.md](docs/roles_and_permissions.md) for an overview of all roles and permissions and the location where they are configured.

## Getting started

### Prerequisites

-   An up-to-date [Docker (Desktop)](https://www.docker.com/products/docker-desktop/) installation

### Setup CMS

1. Open a new terminal at `/src/cms`
2. Create an `.env` file by copying the `./.env.template` to `./.env` and optionally set the `SESSION_DRIVER` to `file`
3. Setup docker using laravel/sail by running:

    ```
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php82-composer:latest \
        composer install --ignore-platform-reqs
    ```

    For more information see: https://laravel.com/docs/10.x/sail#installing-composer-dependencies-for-existing-projects

    (The steps below assume you have an alias for `./vendor/bin/sail`)

4. Start the container by running `sail up -d`
5. Run `sail artisan key:generate` to generate a new application key
6. Run `sail artisan migrate:fresh --seed` to (re)run all migrations and default seeder

As a result of these steps, you have created your local docker working directory, a database and seeded it with a user.

### Setup Public website

We now need the Public website script to build the static files within your container.

1. Open the shell with `sail shell`
2. Run `npm ci` (NPM clean install) to install the required dependencies. If you visit your local website (in your browser) you should see a warning that says something like `Vite manifest not found at: /var/www/html/public/build/manifest.json`.
3. Run `npm run build` (within the shell) to build the static files. This will generate the static files in the `public` folder.

As a result of these steps, you have created the static files for the public website and in your browser you can see the Login page.

### Login to the CMS

1. Visit the project in your browser
2. Login with the following credentials:
    - Email: `admin@minvws.nl` (this user is added with the TestDataSeeder)
3. Open your local Mailpit instance to see the email that is send
4. Click on the link in the email to login
5. Add the 2FA code which you do not have

#### 2FA options

To be able to login, you have three options:

A. Set ENV variable `ONE_TIME_PASSWORD_DRIVER` to `fake` in your `.env` file
   1. Open the `.env` file
   2. Add `ONE_TIME_PASSWORD_DRIVER=fake` to the file
   3. Visit your local default project url again and use a random 6-digit code for 2FA

B. Disable 2FA for the added user
  1. `sail shell` to enter the Shell
  2. `php artisan app:user-disable-otp`
  3. add the email again and press Enter
  4. Visit your local default project url again and you are now logged in

C. Create a new admin user with 2FA disabled
   1. `sail shell` to enter the Shell
   2. `php artisan make:admin-user`
   3. add the name and desired (fake) email you want to use to login
   4. Visit your local default project url again and login (with the email you just added)

Note: to actually use the CMS, you must have 2FA activated.

### Local CI checks

The current CI workflow consists of static code analysis and automated tests. The latter requires a local 'testing' database.
You can use the testing database (which is available by default), which requires to run the migrations there:

1. Bash into the sail-container: `php artisan sail`
2. Run `DB_DATABASE=testing php artisan migrate:fresh --seed` to (re)run all migrations and default seeders
3. Run the test: `php artisan test` (optionally with the `--coverage` parameter)

#### Alternative
Execute the following bin script to run all CI checks: `./bin/ci-local`

## Workflows

-   rdo-package.yml
    -   Build the zip file (used by iRealisatie) for the dataprocessing register
