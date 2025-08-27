<?php

declare(strict_types=1);

namespace App\Providers;

use App\Config\Config;
use App\Routing\UrlGenerator;
use App\Services\Virusscanner\Virusscanner;
use App\Services\Virusscanner\VirusscannerManager;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\ServiceProvider;
use Webmozart\Assert\Assert;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Date::use(CarbonImmutable::class);
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

        $this->app->bind(Virusscanner::class, static function (Application $application): Virusscanner {
            /** @var VirusscannerManager $virusscannerManager */
            $virusscannerManager = $application->get(VirusscannerManager::class);
            $virusscanner = $virusscannerManager->driver(Config::string('virusscanner.default'));
            Assert::isInstanceOf($virusscanner, Virusscanner::class);

            return $virusscanner;
        });
    }
}
