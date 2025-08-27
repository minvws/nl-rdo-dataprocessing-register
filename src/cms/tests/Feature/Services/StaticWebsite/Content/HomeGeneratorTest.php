<?php

declare(strict_types=1);

use App\Models\PublicWebsite;
use App\Services\StaticWebsite\Content\HomeGenerator;
use App\Services\StaticWebsite\StaticWebsiteFilesystem;

it('will generate the index', function (): void {
    PublicWebsite::factory()->create();

    $this->mock(StaticWebsiteFilesystem::class)
        ->shouldReceive('write')
        ->once()
        ->withSomeOfArgs('_index.html');

    $homeGenerator = $this->app->get(HomeGenerator::class);
    $homeGenerator->generate();
});
