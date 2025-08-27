<?php

declare(strict_types=1);

use App\Jobs\PublicWebsite\HugoWebsiteGeneratorJob;
use App\Repositories\AdminLogRepository;
use App\Services\PublicWebsite\HugoPublicWebsiteGenerator;

it('can run the job', function (): void {
    $adminLogRepository = $this->app->get(AdminLogRepository::class);

    $hugoWebsiteGenerator = $this->mock(HugoPublicWebsiteGenerator::class)
        ->shouldReceive('generate')
        ->once()
        ->getMock();

    $hugoWebsiteGeneratorJob = new HugoWebsiteGeneratorJob();
    $hugoWebsiteGeneratorJob->handle($adminLogRepository, $hugoWebsiteGenerator);
});
