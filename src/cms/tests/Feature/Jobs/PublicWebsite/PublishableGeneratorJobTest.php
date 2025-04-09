<?php

declare(strict_types=1);

use App\Jobs\PublicWebsite\PublishableGeneratorJob;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Services\PublicWebsite\Content\PublishableGenerator;

it('can run the job', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create();

    $publishableGenerator = $this->createMock(PublishableGenerator::class);
    $publishableGenerator->expects($this->once())
        ->method('generate')
        ->with($avgResponsibleProcessingRecord);

    $publishableGeneratorJob = new PublishableGeneratorJob($avgResponsibleProcessingRecord);
    $publishableGeneratorJob->handle($publishableGenerator);
});

it('uses the publishable public identifier as the unique id of the job', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create();
    $job = new PublishableGeneratorJob($avgResponsibleProcessingRecord);

    expect($job->uniqueId())->toBe($avgResponsibleProcessingRecord->getPublicIdentifier());
});
