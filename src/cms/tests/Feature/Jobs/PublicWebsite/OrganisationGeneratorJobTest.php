<?php

declare(strict_types=1);

use App\Jobs\PublicWebsite\OrganisationGeneratorJob;
use App\Models\Organisation;
use App\Services\PublicWebsite\Content\OrganisationGenerator;

it('can run the job', function (): void {
    $organisation = Organisation::factory()
        ->create();

    $organisationGenerator = $this->createMock(OrganisationGenerator::class);
    $organisationGenerator->expects($this->once())
        ->method('generate')
        ->with($organisation);

    $organisationGeneratorJob = new OrganisationGeneratorJob($organisation);
    $organisationGeneratorJob->handle($organisationGenerator);
});
