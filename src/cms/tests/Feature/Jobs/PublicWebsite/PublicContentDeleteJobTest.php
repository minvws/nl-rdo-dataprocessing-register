<?php

declare(strict_types=1);

use App\Jobs\PublicWebsite\PublicContentDeleteJob;
use App\Services\PublicWebsite\ContentGenerator;

it('can run the job', function (): void {
    $contentGenerator = $this->createMock(ContentGenerator::class);
    $contentGenerator->expects($this->once())
        ->method('delete');

    $publicContentDeleteJob = new PublicContentDeleteJob();
    $publicContentDeleteJob->handle($contentGenerator);
});
