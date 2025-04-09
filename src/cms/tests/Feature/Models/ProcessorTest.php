<?php

declare(strict_types=1);

use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Processor;
use App\Models\Wpg\WpgProcessingRecord;

it('has avg processor processing records', function (): void {
    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::factory()
        ->create();
    $processor = Processor::factory()
        ->hasAttached($avgProcessorProcessingRecord)
        ->create();

    expect($processor->avgProcessorProcessingRecords()->first()->id)
        ->toBe($avgProcessorProcessingRecord->id);
});

it('has avg responsible processing records', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create();
    $processor = Processor::factory()
        ->hasAttached($avgResponsibleProcessingRecord)
        ->create();

    expect($processor->avgResponsibleProcessingRecords()->first()->id)
        ->toBe($avgResponsibleProcessingRecord->id);
});

it('has wpg processing records', function (): void {
    $wpgProcessingRecord = WpgProcessingRecord::factory()
        ->create();
    $processor = Processor::factory()
        ->hasAttached($wpgProcessingRecord)
        ->create();

    expect($processor->wpgProcessingRecords()->first()->id)
        ->toBe($wpgProcessingRecord->id);
});
