<?php

declare(strict_types=1);

use App\Jobs\PublicWebsite\PublicWebsiteCheckJob;
use Illuminate\Support\Facades\Queue;

it('can run the command', function (): void {
    Queue::fake();

    $this->artisan('public-website:check')
        ->assertExitCode(0);

    Queue::assertPushed(PublicWebsiteCheckJob::class);
});
