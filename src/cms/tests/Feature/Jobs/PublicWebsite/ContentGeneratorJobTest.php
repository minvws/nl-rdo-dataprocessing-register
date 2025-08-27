<?php

declare(strict_types=1);

use App\Jobs\PublicWebsite\ContentGeneratorJob;
use App\Repositories\AdminLogRepository;
use App\Services\PublicWebsite\ContentGenerator;

it('can run the job', function (): void {
    $adminLogRepository = $this->app->get(AdminLogRepository::class);

    $contentGenerator = $this->mock(ContentGenerator::class)
        ->shouldReceive('generate')
        ->once()
        ->getMock();

    $contentGeneratorJob = new ContentGeneratorJob();
    $contentGeneratorJob->handle($adminLogRepository, $contentGenerator);
});
