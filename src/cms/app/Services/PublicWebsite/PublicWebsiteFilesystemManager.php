<?php

declare(strict_types=1);

namespace App\Services\PublicWebsite;

use App\Config\Config;
use Illuminate\Support\Manager;

class PublicWebsiteFilesystemManager extends Manager
{
    public function createFakeDriver(): FakeFilesystem
    {
        /** @var FakeFilesystem $fakeFilesystem */
        $fakeFilesystem = $this->container->get(FakeFilesystem::class);

        return $fakeFilesystem;
    }

    public function createHugoDriver(): HugoFilesystem
    {
        /** @var HugoFilesystem $hugoFilesystem */
        $hugoFilesystem = $this->container->get(HugoFilesystem::class);

        return $hugoFilesystem;
    }

    public function getDefaultDriver(): string
    {
        return Config::string('public-website.filesystem');
    }
}
