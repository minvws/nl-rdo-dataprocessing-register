<?php

declare(strict_types=1);

use App\Services\PublicWebsite\Content\OrganisationListGenerator;
use App\Services\PublicWebsite\PublicWebsiteFilesystem;

it('will generate the index', function (): void {
    $this->mock(PublicWebsiteFilesystem::class)
        ->shouldReceive('write')
        ->once()
        ->withSomeOfArgs('organisatie/_index.html');

    $organisationListGenerator = $this->app->get(OrganisationListGenerator::class);
    $organisationListGenerator->generate();
});
