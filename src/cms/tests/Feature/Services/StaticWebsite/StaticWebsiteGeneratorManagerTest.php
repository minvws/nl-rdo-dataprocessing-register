<?php

declare(strict_types=1);

use App\Services\StaticWebsite\FakeStaticWebsiteGenerator;
use App\Services\StaticWebsite\HugoStaticWebsiteGenerator;
use App\Services\StaticWebsite\StaticWebsiteGenerator;
use App\Services\StaticWebsite\StaticWebsiteGeneratorManager;
use Tests\Helpers\ConfigTestHelper;

it('can bind the implementation on the interface', function (): void {
    ConfigTestHelper::set('public-website.public_website_generator', 'fake');

    $publicWebsiteGenerator = $this->app->get(StaticWebsiteGenerator::class);

    expect($publicWebsiteGenerator)
        ->toBeInstanceOf(FakeStaticWebsiteGenerator::class);
});

it('can load the default driver', function (): void {
    $driver = fake()->word();
    ConfigTestHelper::set('static-website.static_website_generator', $driver);

    $staticWebsiteGeneratorManager = new StaticWebsiteGeneratorManager($this->app);

    expect($staticWebsiteGeneratorManager->getDefaultDriver())
        ->toBe($driver);
});

it('can load the fake driver', function (): void {
    ConfigTestHelper::set('static-website.static_website_generator', 'fake');

    $staticWebsiteGeneratorManager = new StaticWebsiteGeneratorManager($this->app);

    expect($staticWebsiteGeneratorManager->driver())
        ->toBeInstanceOf(FakeStaticWebsiteGenerator::class);
});

it('can load the hugo driver', function (): void {
    ConfigTestHelper::set('static-website.static_website_generator', 'hugo');

    $staticWebsiteGeneratorManager = new StaticWebsiteGeneratorManager($this->app);

    expect($staticWebsiteGeneratorManager->driver())
        ->toBeInstanceOf(HugoStaticWebsiteGenerator::class);
});
