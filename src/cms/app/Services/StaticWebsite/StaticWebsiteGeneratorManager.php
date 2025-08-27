<?php

declare(strict_types=1);

namespace App\Services\StaticWebsite;

use App\Config\Config;
use Illuminate\Support\Manager;

class StaticWebsiteGeneratorManager extends Manager
{
    public function createFakeDriver(): FakeStaticWebsiteGenerator
    {
        /** @var FakeStaticWebsiteGenerator $fakeStaticWebsiteGenerator */
        $fakeStaticWebsiteGenerator = $this->container->get(FakeStaticWebsiteGenerator::class);

        return $fakeStaticWebsiteGenerator;
    }

    public function createHugoDriver(): HugoStaticWebsiteGenerator
    {
        /** @var HugoStaticWebsiteGenerator $hugoStaticWebsiteGenerator */
        $hugoStaticWebsiteGenerator = $this->container->get(HugoStaticWebsiteGenerator::class);

        return $hugoStaticWebsiteGenerator;
    }

    public function getDefaultDriver(): string
    {
        return Config::string('static-website.static_website_generator');
    }
}
