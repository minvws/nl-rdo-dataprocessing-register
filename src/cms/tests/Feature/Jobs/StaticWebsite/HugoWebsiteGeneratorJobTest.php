<?php

declare(strict_types=1);

use App\Jobs\StaticWebsite\HugoWebsiteGeneratorJob;
use App\Repositories\AdminLogRepository;
use App\Services\StaticWebsite\HugoStaticWebsiteGenerator;

it('can run the job', function (): void {
    $adminLogRepository = $this->app->get(AdminLogRepository::class);

    $hugoWebsiteGenerator = $this->mock(HugoStaticWebsiteGenerator::class)
        ->shouldReceive('generate')
        ->once()
        ->getMock();

    $hugoWebsiteGeneratorJob = new HugoWebsiteGeneratorJob();
    $hugoWebsiteGeneratorJob->handle($adminLogRepository, $hugoWebsiteGenerator);
});
