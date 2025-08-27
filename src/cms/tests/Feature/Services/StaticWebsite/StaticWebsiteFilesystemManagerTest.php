<?php

declare(strict_types=1);

use App\Services\StaticWebsite\FakeFilesystem;
use App\Services\StaticWebsite\HugoFilesystem;
use App\Services\StaticWebsite\StaticWebsiteFilesystemManager;
use Tests\Helpers\ConfigTestHelper;

it('can load the default driver', function (): void {
    $driver = fake()->word();
    ConfigTestHelper::set('static-website.filesystem', $driver);

    $staticWebsiteFilesystemManager = new StaticWebsiteFilesystemManager($this->app);

    expect($staticWebsiteFilesystemManager->getDefaultDriver())
        ->toBe($driver);
});

it('can load the fake driver', function (): void {
    ConfigTestHelper::set('static-website.filesystem', 'fake');

    $staticWebsiteFilesystemManager = new StaticWebsiteFilesystemManager($this->app);

    expect($staticWebsiteFilesystemManager->driver())
        ->toBeInstanceOf(FakeFilesystem::class);
});

it('can load the hugo driver', function (): void {
    ConfigTestHelper::set('static-website.filesystem', 'hugo');

    $staticWebsiteFilesystemManager = new StaticWebsiteFilesystemManager($this->app);

    expect($staticWebsiteFilesystemManager->driver())
        ->toBeInstanceOf(HugoFilesystem::class);
});
