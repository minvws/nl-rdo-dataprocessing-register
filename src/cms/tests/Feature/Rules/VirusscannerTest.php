<?php

declare(strict_types=1);

use App\Rules\Virusscanner as VirusscannerRule;
use App\Services\Virusscanner\Virusscanner as VirusscannerService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Psr\Log\NullLogger;

it('fails if no uploadedfile provided', function (): void {
    $validated = true;

    $virusscannerRule = $this->app->get(VirusscannerRule::class);
    $virusscannerRule->validate(fake()->word(), fake()->word(), function () use (&$validated): void {
        $validated = false;
    });

    expect($validated)->toBeFalse();
});

it('validates with a real file', function (): void {
    $validated = true;

    $virusscannerService = $this->mock(VirusscannerService::class)
        ->shouldReceive('isResourceClean')
        ->once()
        ->andReturn(true)
        ->getMock();
    $virusscannerRule = new VirusscannerRule(new NullLogger(), $virusscannerService);

    $file = sprintf('/tmp/%s.%s', fake()->uuid(), fake()->fileExtension());
    File::put($file, fake()->word());
    $uploadedFile = new UploadedFile($file, fake()->word());

    $virusscannerRule->validate(fake()->word(), $uploadedFile, function () use (&$validated): void {
        $validated = false;
    });

    expect($validated)->toBeTrue();
});

it('fails when virus is reported', function (): void {
    $validated = true;

    $virusscannerService = $this->mock(VirusscannerService::class)
        ->shouldReceive('isResourceClean')
        ->andReturn(false)
        ->getMock();
    $virusscannerRule = new VirusscannerRule(new NullLogger(), $virusscannerService);

    $file = sprintf('/tmp/%s.%s', fake()->uuid(), fake()->fileExtension());
    File::put($file, fake()->word());
    $uploadedFile = new UploadedFile($file, fake()->word());

    $virusscannerRule->validate(fake()->word(), $uploadedFile, function () use (&$validated): void {
        $validated = false;
    });

    expect($validated)->toBeFalse();
});
