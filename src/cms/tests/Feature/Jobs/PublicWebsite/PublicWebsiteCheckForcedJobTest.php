<?php

declare(strict_types=1);

use App\Jobs\PublicWebsite\PublicWebsiteCheckForcedJob;
use App\Services\PublicWebsite\PublicSitemapCheckJsonException;
use App\Services\PublicWebsite\PublicSitemapCheckRequestException;
use App\Services\PublicWebsite\PublicWebsiteCheckService;
use Psr\Log\NullLogger;

it('can run the job', function (): void {
    $publicWebsiteCheckService = $this->mock(PublicWebsiteCheckService::class)
        ->shouldReceive('checkForced')
        ->once()
        ->getMock();

    $publicWebsiteCheckJob = new PublicWebsiteCheckForcedJob()->withFakeQueueInteractions();
    $publicWebsiteCheckJob->handle(new NullLogger(), $publicWebsiteCheckService);
    $publicWebsiteCheckJob->assertNotFailed();
});

it('fails on exception', function (): void {
    $publicWebsiteCheckService = $this->mock(PublicWebsiteCheckService::class)
        ->shouldReceive('checkForced')
        ->once()
        ->andThrow(new PublicSitemapCheckJsonException())
        ->getMock();

    $publicWebsiteCheckJob = new PublicWebsiteCheckForcedJob()->withFakeQueueInteractions();
    $publicWebsiteCheckJob->handle(new NullLogger(), $publicWebsiteCheckService);
    $publicWebsiteCheckJob->assertNotFailed();
    $publicWebsiteCheckJob->assertNotReleased();
});

it('retries on retry exception', function (): void {
    $publicWebsiteCheckService = $this->mock(PublicWebsiteCheckService::class)
        ->shouldReceive('checkForced')
        ->once()
        ->andThrow(new PublicSitemapCheckRequestException())
        ->getMock();

    $publicWebsiteCheckJob = new PublicWebsiteCheckForcedJob()->withFakeQueueInteractions();
    $publicWebsiteCheckJob->handle(new NullLogger(), $publicWebsiteCheckService);
    $publicWebsiteCheckJob->assertNotFailed();
    $publicWebsiteCheckJob->assertReleased(60);
});
