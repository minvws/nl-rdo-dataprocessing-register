<?php

declare(strict_types=1);

use App\Jobs\PublicWebsite\PublicWebsiteCheckJob;
use App\Services\PublicWebsite\PublicSitemapCheckException;
use App\Services\PublicWebsite\PublicSitemapCheckRetryException;
use App\Services\PublicWebsite\PublicWebsiteCheckService;
use Psr\Log\NullLogger;

it('can run the job', function (): void {
    $publicWebsiteCheckService = $this->createMock(PublicWebsiteCheckService::class);
    $publicWebsiteCheckService->expects($this->once())
        ->method('check');

    $publicWebsiteCheckJob = (new PublicWebsiteCheckJob())->withFakeQueueInteractions();
    $publicWebsiteCheckJob->handle(new NullLogger(), $publicWebsiteCheckService);
    $publicWebsiteCheckJob->assertNotFailed();
});

it('fails on exception', function (): void {
    $publicWebsiteCheckService = $this->createMock(PublicWebsiteCheckService::class);
    $publicWebsiteCheckService->expects($this->once())
        ->method('check')
        ->willThrowException(new PublicSitemapCheckException());

    $publicWebsiteCheckJob = (new PublicWebsiteCheckJob())->withFakeQueueInteractions();
    $publicWebsiteCheckJob->handle(new NullLogger(), $publicWebsiteCheckService);
    $publicWebsiteCheckJob->assertNotFailed();
    $publicWebsiteCheckJob->assertNotReleased();
});

it('retries on retry exception', function (): void {
    $publicWebsiteCheckService = $this->createMock(PublicWebsiteCheckService::class);
    $publicWebsiteCheckService->expects($this->once())
        ->method('check')
        ->willThrowException(new PublicSitemapCheckRetryException());

    $publicWebsiteCheckJob = (new PublicWebsiteCheckJob())->withFakeQueueInteractions();
    $publicWebsiteCheckJob->handle(new NullLogger(), $publicWebsiteCheckService);
    $publicWebsiteCheckJob->assertNotFailed();
    $publicWebsiteCheckJob->assertReleased(60);
});
