<?php

declare(strict_types=1);

use App\Jobs\StaticWebsite\StaticWebsiteCheckForcedJob;
use App\Services\StaticWebsite\StaticSitemapCheckJsonException;
use App\Services\StaticWebsite\StaticSitemapCheckRequestException;
use App\Services\StaticWebsite\StaticWebsiteCheckService;
use Psr\Log\NullLogger;

it('can run the job', function (): void {
    $staticWebsiteCheckService = $this->mock(StaticWebsiteCheckService::class)
        ->shouldReceive('checkForced')
        ->once()
        ->getMock();

    $staticWebsiteCheckJob = new StaticWebsiteCheckForcedJob()->withFakeQueueInteractions();
    $staticWebsiteCheckJob->handle(new NullLogger(), $staticWebsiteCheckService);
    $staticWebsiteCheckJob->assertNotFailed();
});

it('fails on exception', function (): void {
    $staticWebsiteCheckService = $this->mock(StaticWebsiteCheckService::class)
        ->shouldReceive('checkForced')
        ->once()
        ->andThrow(new StaticSitemapCheckJsonException())
        ->getMock();

    $staticWebsiteCheckJob = new StaticWebsiteCheckForcedJob()->withFakeQueueInteractions();
    $staticWebsiteCheckJob->handle(new NullLogger(), $staticWebsiteCheckService);
    $staticWebsiteCheckJob->assertNotFailed();
    $staticWebsiteCheckJob->assertNotReleased();
});

it('retries on retry exception', function (): void {
    $staticWebsiteCheckService = $this->mock(StaticWebsiteCheckService::class)
        ->shouldReceive('checkForced')
        ->once()
        ->andThrow(new StaticSitemapCheckRequestException())
        ->getMock();

    $staticWebsiteCheckJob = new StaticWebsiteCheckForcedJob()->withFakeQueueInteractions();
    $staticWebsiteCheckJob->handle(new NullLogger(), $staticWebsiteCheckService);
    $staticWebsiteCheckJob->assertNotFailed();
    $staticWebsiteCheckJob->assertReleased(60);
});
