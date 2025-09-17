<?php

declare(strict_types=1);

use App\Jobs\StaticWebsite\StaticWebsiteCheckForcedJob;
use App\Jobs\StaticWebsite\StaticWebsiteCheckJob;
use Illuminate\Support\Facades\Queue;

it('can run the command', function (): void {
    Queue::fake();

    $this->artisan('static-website:check')
        ->assertExitCode(0);

    Queue::assertPushed(StaticWebsiteCheckJob::class);
});

it('can run the command with force', function (): void {
    Queue::fake();

    $this->artisan('static-website:check -F')
        ->assertExitCode(0);

    Queue::assertPushed(StaticWebsiteCheckForcedJob::class);
});
