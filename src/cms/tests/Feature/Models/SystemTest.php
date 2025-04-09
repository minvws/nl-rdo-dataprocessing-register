<?php

declare(strict_types=1);

use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\System;
use App\Models\Wpg\WpgProcessingRecord;

it('has avg processor processing records', function (): void {
    $system = System::factory()
        ->withAvgProcessorProcessingRecord()
        ->create();

    expect($system->avgProcessorProcessingRecords()->first())
        ->toBeInstanceOf(AvgProcessorProcessingRecord::class);
});

it('has avg responsible processing records', function (): void {
    $system = System::factory()
        ->withAvgResponsibleProcessingRecord()
        ->create();

    expect($system->avgResponsibleProcessingRecords()->first())
        ->toBeInstanceOf(AvgResponsibleProcessingRecord::class);
});

it('has wpg processing records', function (): void {
    $system = System::factory()
        ->withWpgProcessingRecord()
        ->create();

    expect($system->wpgProcessingRecords()->first())
        ->toBeInstanceOf(WpgProcessingRecord::class);
});

it('can retrieve the display name', function (): void {
    $description = fake()->optional()->sentence();

    $system = System::factory()
        ->create([
            'description' => $description,
        ]);

    expect($system->getDisplayName())
        ->toBe($description ?? '—');
});
