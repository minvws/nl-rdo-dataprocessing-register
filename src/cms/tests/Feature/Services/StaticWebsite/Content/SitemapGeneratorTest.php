<?php

declare(strict_types=1);

use App\Services\StaticWebsite\Content\SitemapGenerator;
use App\Services\StaticWebsite\StaticWebsiteFilesystem;

it('will generate the index', function (): void {
    $this->mock(StaticWebsiteFilesystem::class)
        ->shouldReceive('write')
        ->once()
        ->withSomeOfArgs('sitemap.html');

    $sitemapGenerator = $this->app->get(SitemapGenerator::class);
    $sitemapGenerator->generate();
});
