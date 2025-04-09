<?php

declare(strict_types=1);

namespace App\Services\PublicWebsite;

use App\Config\Config;
use Illuminate\Support\Manager;

class PublicWebsiteGeneratorManager extends Manager
{
    public function createFakeDriver(): FakePublicWebsiteGenerator
    {
        /** @var FakePublicWebsiteGenerator $fakePublicWebsiteGenerator */
        $fakePublicWebsiteGenerator = $this->container->get(FakePublicWebsiteGenerator::class);

        return $fakePublicWebsiteGenerator;
    }

    public function createHugoDriver(): HugoPublicWebsiteGenerator
    {
        /** @var HugoPublicWebsiteGenerator $hugoPublicWebsiteGenerator */
        $hugoPublicWebsiteGenerator = $this->container->get(HugoPublicWebsiteGenerator::class);

        return $hugoPublicWebsiteGenerator;
    }

    public function getDefaultDriver(): string
    {
        return Config::string('public-website.public_website_generator');
    }
}
