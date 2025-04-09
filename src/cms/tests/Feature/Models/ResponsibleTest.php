<?php

declare(strict_types=1);

use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Responsible;
use App\Models\Wpg\WpgProcessingRecord;

it('has avg processor processing records', function (): void {
    $responsible = Responsible::factory()
        ->withAvgProcessorProcessingRecord()
        ->create();

    expect($responsible->avgProcessorProcessingRecords()->first())
        ->toBeInstanceOf(AvgProcessorProcessingRecord::class);
});

it('has avg responsible processing records', function (): void {
    $responsible = Responsible::factory()
        ->withAvgResponsibleProcessingRecord()
        ->create();

    expect($responsible->avgResponsibleProcessingRecords()->first())
        ->toBeInstanceOf(AvgResponsibleProcessingRecord::class);
});

it('has wpg processing records', function (): void {
    $responsible = Responsible::factory()
        ->withWpgProcessingRecord()
        ->create();

    expect($responsible->wpgProcessingRecords()->first())
        ->toBeInstanceOf(WpgProcessingRecord::class);
});
