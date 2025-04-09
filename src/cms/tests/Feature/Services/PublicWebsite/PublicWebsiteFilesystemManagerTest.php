<?php

declare(strict_types=1);

use App\Services\PublicWebsite\FakeFilesystem;
use App\Services\PublicWebsite\HugoFilesystem;
use App\Services\PublicWebsite\PublicWebsiteFilesystemManager;
use Tests\Helpers\ConfigHelper;

it('can load the default driver', function (): void {
    $driver = fake()->word();
    ConfigHelper::set('public-website.filesystem', $driver);

    $publicWebsiteFilesystemManager = new PublicWebsiteFilesystemManager($this->app);

    expect($publicWebsiteFilesystemManager->getDefaultDriver())
        ->toBe($driver);
});

it('can load the fake driver', function (): void {
    ConfigHelper::set('public-website.filesystem', 'fake');

    $publicWebsiteFilesystemManager = new PublicWebsiteFilesystemManager($this->app);

    expect($publicWebsiteFilesystemManager->driver())
        ->toBeInstanceOf(FakeFilesystem::class);
});

it('can load the hugo driver', function (): void {
    ConfigHelper::set('public-website.filesystem', 'hugo');

    $publicWebsiteFilesystemManager = new PublicWebsiteFilesystemManager($this->app);

    expect($publicWebsiteFilesystemManager->driver())
        ->toBeInstanceOf(HugoFilesystem::class);
});
