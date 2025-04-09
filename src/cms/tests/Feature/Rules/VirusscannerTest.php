<?php

declare(strict_types=1);

use App\Rules\Virusscanner;
use App\Services\VirusscannerService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Mockery\MockInterface;
use Psr\Log\NullLogger;

it('fails if no uploadedfile provided', function (): void {
    $validated = true;

    $virusscanner = $this->app->get(Virusscanner::class);
    $virusscanner->validate(fake()->word(), fake()->word(), function () use (&$validated): void {
        $validated = false;
    });

    expect($validated)->toBeFalse();
});

it('validates with a real file', function (): void {
    $validated = true;

    $virusscannerService = $this->mock(VirusscannerService::class, function (MockInterface $mock): void {
        $mock->expects('isResourceClean')
            ->andReturn(true);
    });
    $virusscanner = new Virusscanner(new NullLogger(), $virusscannerService);

    $file = sprintf('/tmp/%s.%s', fake()->uuid(), fake()->fileExtension());
    File::put($file, fake()->word());
    $uploadedFile = new UploadedFile($file, fake()->word());

    $virusscanner->validate(fake()->word(), $uploadedFile, function () use (&$validated): void {
        $validated = false;
    });

    expect($validated)->toBeTrue();
});

it('fails when virus is reported', function (): void {
    $validated = true;

    $virusscannerService = $this->mock(VirusscannerService::class, function (MockInterface $mock): void {
        $mock->expects('isResourceClean')
            ->andReturn(false);
    });
    $virusscanner = new Virusscanner(new NullLogger(), $virusscannerService);

    $file = sprintf('/tmp/%s.%s', fake()->uuid(), fake()->fileExtension());
    File::put($file, fake()->word());
    $uploadedFile = new UploadedFile($file, fake()->word());

    $virusscanner->validate(fake()->word(), $uploadedFile, function () use (&$validated): void {
        $validated = false;
    });

    expect($validated)->toBeFalse();
});
