<?php

declare(strict_types=1);

use App\Services\PublicWebsite\FakePublicWebsiteGenerator;
use App\Services\PublicWebsite\HugoPublicWebsiteGenerator;
use App\Services\PublicWebsite\PublicWebsiteGenerator;
use App\Services\PublicWebsite\PublicWebsiteGeneratorManager;
use Tests\Helpers\ConfigTestHelper;

it('can bind the implementation on the interface', function (): void {
    ConfigTestHelper::set('public-website.public_website_generator', 'fake');

    $publicWebsiteGenerator = $this->app->get(PublicWebsiteGenerator::class);

    expect($publicWebsiteGenerator)
        ->toBeInstanceOf(FakePublicWebsiteGenerator::class);
});

it('can load the default driver', function (): void {
    $driver = fake()->word();
    ConfigTestHelper::set('public-website.public_website_generator', $driver);

    $publicWebsiteGeneratorManager = new PublicWebsiteGeneratorManager($this->app);

    expect($publicWebsiteGeneratorManager->getDefaultDriver())
        ->toBe($driver);
});

it('can load the fake driver', function (): void {
    ConfigTestHelper::set('public-website.public_website_generator', 'fake');

    $publicWebsiteGeneratorManager = new PublicWebsiteGeneratorManager($this->app);

    expect($publicWebsiteGeneratorManager->driver())
        ->toBeInstanceOf(FakePublicWebsiteGenerator::class);
});

it('can load the hugo driver', function (): void {
    ConfigTestHelper::set('public-website.public_website_generator', 'hugo');

    $publicWebsiteGeneratorManager = new PublicWebsiteGeneratorManager($this->app);

    expect($publicWebsiteGeneratorManager->driver())
        ->toBeInstanceOf(HugoPublicWebsiteGenerator::class);
});
