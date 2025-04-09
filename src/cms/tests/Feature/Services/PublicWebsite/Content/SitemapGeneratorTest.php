<?php

declare(strict_types=1);

use App\Services\PublicWebsite\Content\SitemapGenerator;
use App\Services\PublicWebsite\PublicWebsiteFilesystem;

it('will generate the index', function (): void {
    $publicWebsiteFilesystem = $this->createMock(PublicWebsiteFilesystem::class);
    $publicWebsiteFilesystem->expects($this->once())
        ->method('write')
        ->with('sitemap.html');

    /** @var SitemapGenerator $sitemapGenerator */
    $sitemapGenerator = $this->app->make(SitemapGenerator::class, ['publicWebsiteFilesystem' => $publicWebsiteFilesystem]);
    $sitemapGenerator->generate();
});
