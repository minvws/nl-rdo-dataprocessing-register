<?php

declare(strict_types=1);

use App\Jobs\PublicWebsite\PublicWebsiteCheckForcedJob;
use App\Jobs\PublicWebsite\PublicWebsiteCheckJob;
use Illuminate\Support\Facades\Queue;

it('can run the command', function (): void {
    Queue::fake();

    $this->artisan('public-website:check')
        ->assertExitCode(0);

    Queue::assertPushed(PublicWebsiteCheckJob::class);
});

it('can run the command with force', function (): void {
    Queue::fake();

    $this->artisan('public-website:check -F')
        ->assertExitCode(0);

    Queue::assertPushed(PublicWebsiteCheckForcedJob::class);
});
