<?php

declare(strict_types=1);

use App\Jobs\PublicWebsite\HugoWebsiteGeneratorJob;
use App\Services\PublicWebsite\HugoPublicWebsiteGenerator;

it('can run the job', function (): void {
    $hugoWebsiteGenerator = $this->createMock(HugoPublicWebsiteGenerator::class);
    $hugoWebsiteGenerator->expects($this->once())
        ->method('generate');

    $hugoWebsiteGeneratorJob = new HugoWebsiteGeneratorJob();
    $hugoWebsiteGeneratorJob->handle($hugoWebsiteGenerator);
});
