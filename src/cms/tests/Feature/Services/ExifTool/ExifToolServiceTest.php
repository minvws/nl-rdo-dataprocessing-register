<?php

declare(strict_types=1);

use App\Services\Media\ExifToolService;
use Illuminate\Process\PendingProcess;
use Illuminate\Support\Facades\Process;

it('test it can run pruneExifData', function (): void {
    $path = fake()->uuid();
    Process::fake();

    $service = $this->app->get(ExifToolService::class);
    $service->pruneExifData($path);

    Process::assertRan(static function (PendingProcess $pendingProcess) use ($path) {
        return $pendingProcess->command === ['exiftool', '-all=', $path];
    });
});
