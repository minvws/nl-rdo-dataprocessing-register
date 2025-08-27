<?php

declare(strict_types=1);

namespace App\Providers;

use App\Config\Config;
use App\Listeners\PublicWebsite\AfterBuildHandler;
use App\Services\PublicWebsite\HugoFilesystem;
use App\Services\PublicWebsite\HugoPublicWebsiteGenerator;
use App\Services\PublicWebsite\PublicWebsiteCheckService;
use App\Services\PublicWebsite\PublicWebsiteFilesystem;
use App\Services\PublicWebsite\PublicWebsiteFilesystemManager;
use App\Services\PublicWebsite\PublicWebsiteGenerator;
use App\Services\PublicWebsite\PublicWebsiteGeneratorManager;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Webmozart\Assert\Assert;

class PublicWebsiteServiceProvider extends ServiceProvider
{
    private const PUBLIC_WEBSITE_FILESYSTEM = 'public_website_filesystem';

    public function register(): void
    {
        $this->app->bind(self::PUBLIC_WEBSITE_FILESYSTEM, static function (): Filesystem {
            return Storage::disk(Config::string('public-website.hugo_filesystem_disk'));
        });

        $this->app->bind(PublicWebsiteFilesystem::class, static function (Application $application): PublicWebsiteFilesystem {
            /** @var PublicWebsiteFilesystemManager $publicWebsiteFilesystemManager */
            $publicWebsiteFilesystemManager = $application->get(PublicWebsiteFilesystemManager::class);
            $publicWebsiteFilesystem = $publicWebsiteFilesystemManager->driver(Config::string('public-website.filesystem'));
            Assert::isInstanceOf($publicWebsiteFilesystem, PublicWebsiteFilesystem::class);

            return $publicWebsiteFilesystem;
        });
        $this->app->bind(PublicWebsiteGenerator::class, static function (Application $application): PublicWebsiteGenerator {
            /** @var PublicWebsiteGeneratorManager $publicWebsiteGeneratorManager */
            $publicWebsiteGeneratorManager = $application->get(PublicWebsiteGeneratorManager::class);
            $publicWebsiteGenerator = $publicWebsiteGeneratorManager->driver(Config::string('public-website.website_generator'));
            Assert::isInstanceOf($publicWebsiteGenerator, PublicWebsiteGenerator::class);

            return $publicWebsiteGenerator;
        });
        $this->app->when(HugoFilesystem::class)
            ->needs(Filesystem::class)
            ->give(self::PUBLIC_WEBSITE_FILESYSTEM);
        $this->app->when(HugoFilesystem::class)
            ->needs('$hugoContentFolder')
            ->giveConfig('public-website.hugo_content_folder');

        $this->app->when(HugoPublicWebsiteGenerator::class)
            ->needs(Filesystem::class)
            ->give(self::PUBLIC_WEBSITE_FILESYSTEM);
        $this->app->when(HugoPublicWebsiteGenerator::class)
            ->needs('$baseUrl')
            ->giveConfig('public-website.base_url');
        $this->app->when(HugoPublicWebsiteGenerator::class)
            ->needs('$hugoProjectPath')
            ->giveConfig('public-website.hugo_project_path');
        $this->app->when(HugoPublicWebsiteGenerator::class)
            ->needs('$hugoContentFolder')
            ->giveConfig('public-website.hugo_content_folder');
        $this->app->when(HugoPublicWebsiteGenerator::class)
            ->needs('$publicWebsiteFolder')
            ->giveConfig('public-website.public_website_folder');

        $this->app->when([PublicWebsiteCheckService::class])
            ->needs('$baseUrl')
            ->giveConfig('public-website.check_base_url');

        $this->app->when(AfterBuildHandler::class)
            ->needs('$afterBuildHook')
            ->giveConfig('public-website.build_after_hook');
        $this->app->when(AfterBuildHandler::class)
            ->needs('$planCheckJobDelays')
            ->giveConfig('public-website.plan-check-job-delays');

        $this->app->when([PublicWebsiteCheckService::class])
            ->needs('$baseUrl')
            ->giveConfig('public-website.check_base_url');
        $this->app->when([PublicWebsiteCheckService::class])
            ->needs('$proxy')
            ->giveConfig('public-website.check_proxy');
    }
}
