<?php

declare(strict_types=1);

use App\Services\StaticWebsite\Content\HomeGenerator;
use App\Services\StaticWebsite\Content\PublicWebsiteTreeListGenerator;
use App\Services\StaticWebsite\Content\SitemapGenerator;
use App\Services\StaticWebsite\ContentGenerator;

it('will call the correct generators on generate', function (): void {
    $this->mock(HomeGenerator::class)
        ->shouldReceive('generate')
        ->once();

    $this->mock(PublicWebsiteTreeListGenerator::class)
        ->shouldReceive('generate')
        ->once();

    $this->mock(SitemapGenerator::class)
        ->shouldReceive('generate')
        ->once();

    $contentGenerator = $this->app->get(ContentGenerator::class);
    $contentGenerator->generate();
});
