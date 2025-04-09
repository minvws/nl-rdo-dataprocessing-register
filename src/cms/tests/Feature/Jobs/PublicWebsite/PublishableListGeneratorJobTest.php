<?php

declare(strict_types=1);

use App\Jobs\PublicWebsite\PublishableListGeneratorJob;
use App\Models\Organisation;
use App\Services\PublicWebsite\Content\PublishableListGenerator;

it('can run the job', function (): void {
    $organisation = Organisation::factory()
        ->create();

    $publishableListGenerator = $this->createMock(PublishableListGenerator::class);
    $publishableListGenerator->expects($this->once())
        ->method('generate')
        ->with($organisation);

    $publishableListGeneratorJob = new PublishableListGeneratorJob($organisation);
    $publishableListGeneratorJob->handle($publishableListGenerator);
});
