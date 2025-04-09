<?php

declare(strict_types=1);

use App\Jobs\PublicWebsite\ContentGeneratorJob;
use App\Services\PublicWebsite\ContentGenerator;

it('can run the job', function (): void {
    $contentGenerator = $this->createMock(ContentGenerator::class);
    $contentGenerator->expects($this->once())
        ->method('generate');

    $contentGeneratorJob = new ContentGeneratorJob();
    $contentGeneratorJob->handle($contentGenerator);
});
