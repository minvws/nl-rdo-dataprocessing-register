<?php

declare(strict_types=1);

use App\Services\PublicWebsite\Content\OrganisationListGenerator;
use App\Services\PublicWebsite\PublicWebsiteFilesystem;

it('will generate the index', function (): void {
    $publicWebsiteFilesystem = $this->createMock(PublicWebsiteFilesystem::class);
    $publicWebsiteFilesystem->expects($this->once())
        ->method('write')
        ->with('organisatie/_index.html');

    /** @var OrganisationListGenerator $organisationListGenerator */
    $organisationListGenerator = $this->app->make(
        OrganisationListGenerator::class,
        ['publicWebsiteFilesystem' => $publicWebsiteFilesystem],
    );
    $organisationListGenerator->generate();
});
