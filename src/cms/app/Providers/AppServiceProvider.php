<?php

declare(strict_types=1);

namespace App\Providers;

use App\Config\Config;
use App\Routing\UrlGenerator;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

use function implode;
use function preg_match;
use function sprintf;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Date::use(CarbonImmutable::class);

        Lang::handleMissingKeysUsing(static function (string $key): string {
            // @codeCoverageIgnoreStart
            $keyPrefixesToIgnore = [
                "validation.custom",
                "validation.values",
                "\(and :count more error",
            ];
            if (!preg_match('/^(' . implode('|', $keyPrefixesToIgnore) . ')/', $key)) {
                Log::debug(sprintf('Missing translation key [%s] detected.', $key));
            }
            // @codeCoverageIgnoreEnd
            return $key;
        });
    }

    public function register(): void
    {
        $this->app->singleton('url', static function (Application $app): UrlGenerator {
            /** @var Request $request */
            $request = $app->rebinding('request', static function (Application $app, Request $request): void {
                $app['url']->setRequest($request);
            });

            return new UrlGenerator($app['router']->getRoutes(), $request, Config::stringOrNull('app.asset_url'));
        });
    }
}
