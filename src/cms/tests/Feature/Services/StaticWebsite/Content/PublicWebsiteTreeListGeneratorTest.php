<?php

declare(strict_types=1);

use App\Services\StaticWebsite\Content\PublicWebsiteTreeListGenerator;
use App\Services\StaticWebsite\StaticWebsiteFilesystem;

it('will generate the index', function (): void {
    $this->mock(StaticWebsiteFilesystem::class)
        ->shouldReceive('write')
        ->once()
        ->withSomeOfArgs('organisatie/_index.html');

    $organisationListGenerator = $this->app->get(PublicWebsiteTreeListGenerator::class);
    $organisationListGenerator->generate();
});
