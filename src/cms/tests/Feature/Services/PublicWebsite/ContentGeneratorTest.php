<?php

declare(strict_types=1);

use App\Services\PublicWebsite\Content\HomeGenerator;
use App\Services\PublicWebsite\Content\OrganisationListGenerator;
use App\Services\PublicWebsite\Content\SitemapGenerator;
use App\Services\PublicWebsite\ContentGenerator;

it('will call the correct generators on generate', function (): void {
    $this->mock(HomeGenerator::class)
        ->shouldReceive('generate')
        ->once();
    $this->mock(OrganisationListGenerator::class)
        ->shouldReceive('generate')
        ->once();
    $this->mock(SitemapGenerator::class)
        ->shouldReceive('generate')
        ->once();

    $contentGenerator = $this->app->get(ContentGenerator::class);
    $contentGenerator->generate();
});
