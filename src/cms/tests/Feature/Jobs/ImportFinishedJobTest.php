<?php

declare(strict_types=1);

use App\Jobs\ImportFinishedJob;
use App\Models\User;
use Filament\Notifications\Notification;

it('can run the job', function (): void {
    $zipFilename = fake()->word();
    $user = User::factory()->create();

    $importEntityJob = new ImportFinishedJob($zipFilename, $user->id);
    $importEntityJob->handle();

    Notification::assertNotified();
});
