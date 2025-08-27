<?php

declare(strict_types=1);

use App\Services\PublicWebsite\Content\SitemapGenerator;
use App\Services\PublicWebsite\PublicWebsiteFilesystem;

it('will generate the index', function (): void {
    $this->mock(PublicWebsiteFilesystem::class)
        ->shouldReceive('write')
        ->once()
        ->withSomeOfArgs('sitemap.html');

    $sitemapGenerator = $this->app->get(SitemapGenerator::class);
    $sitemapGenerator->generate();
});
