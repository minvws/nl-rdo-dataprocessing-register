<?php

declare(strict_types=1);

use App\Models\PublicWebsite;
use App\Services\PublicWebsite\Content\HomeGenerator;
use App\Services\PublicWebsite\PublicWebsiteFilesystem;

it('will generate the index', function (): void {
    PublicWebsite::factory()->create();

    $this->mock(PublicWebsiteFilesystem::class)
        ->shouldReceive('write')
        ->once()
        ->withSomeOfArgs('_index.html');

    $homeGenerator = $this->app->get(HomeGenerator::class);
    $homeGenerator->generate();
});
