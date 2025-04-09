<?php

declare(strict_types=1);

use App\Services\PublicWebsite\Content\HomeGenerator;
use App\Services\PublicWebsite\PublicWebsiteFilesystem;

it('will generate the index', function (): void {
    $publicWebsiteFilesystem = $this->createMock(PublicWebsiteFilesystem::class);
    $publicWebsiteFilesystem->expects($this->once())
        ->method('write')
        ->with('_index.html');

    /** @var HomeGenerator $homeGenerator */
    $homeGenerator = $this->app->make(HomeGenerator::class, ['publicWebsiteFilesystem' => $publicWebsiteFilesystem]);
    $homeGenerator->generate();
});
