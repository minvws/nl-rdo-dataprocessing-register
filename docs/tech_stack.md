# Tech stack

Two questions this answers: what a developer needs to know to work on this project, and what a hosting
environment has to provide.

It contains no version numbers. Those live in the manifests and change weekly through Dependabot, see
[Versions](#versions).

## Development

**CMS**, in `src/cms/`. PHP and Laravel, with Filament for the entire admin interface. That covers most
of the work. Filament runs on Livewire, which is worth knowing when a component misbehaves. Views are
Blade, styling is Tailwind, assets are bundled by Vite, tests are written in Pest. The rest of
`composer.json` is ordinary Laravel packages.

**Public website**, in `src/static-website/`. Hugo. Layouts are Go templates and styling is Sass. The
CMS exports JSON and markdown, Hugo turns that into static HTML.

**Database.** PostgreSQL. Migrations are Laravel migrations, occasionally with raw SQL.

**User manual**, in `docs/handleiding/`. Markdown, built to PDF with Pandoc and LuaLaTeX. See its
`Makefile`.

Static analysis and coding standards are enforced in CI by PHPStan with Larastan, PHP_CodeSniffer and
PHPMD. Their configuration lives in `src/cms/`.

## Hosting

| Component | Notes | Where it is configured |
| --- | --- | --- |
| PHP | Extensions `fileinfo`, `pdo`, `sockets` and `zip`. Plus `exiftool` on the system | `src/cms/composer.json`, `src/cms/Dockerfile` |
| PostgreSQL | Primary datastore | `src/cms/docker-compose.yml` |
| S3 compatible object storage | Uploaded documents and the generated website. MinIO locally and in CI | `src/cms/config/filesystems.php` |
| ClamAV | Every upload is scanned before it is stored | `src/cms/docker-compose.yml` |
| SMTP | Login links, approval requests and expiry notifications | `docs/environment_variables.md` |
| Queue worker | Queues `high`, `default` and `low` | `src/cms/composer.json` (`queue-listen`) |
| Scheduler | `php artisan schedule:run` every minute | `src/cms/app/Console/Kernel.php`, see [scheduled_tasks.md](scheduled_tasks.md) |
| Hugo and Dart Sass | Installed in the image, used to generate the public website | `src/cms/Dockerfile` |

The CMS does not need to be reachable from the internet. Only the generated static website is public.
Node.js is needed to build the assets, not to run the application.

## Versions

- `src/cms/composer.json` and `src/cms/package.json` for the declared constraints.
- `src/cms/composer.lock` and `src/cms/package-lock.json` for what is actually installed.
- `src/cms/Dockerfile` for PHP, Hugo and Dart Sass.

## Maintenance

Update this document when a language or framework is added or removed, or when hosting needs something
new. Version upgrades do not touch it, because it holds no versions.
