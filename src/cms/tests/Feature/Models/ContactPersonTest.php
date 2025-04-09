<?php

declare(strict_types=1);

use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\ContactPerson;
use App\Models\Wpg\WpgProcessingRecord;

it('has avg processor processing records', function (): void {
    $contactPerson = ContactPerson::factory()
        ->withAvgProcessorProcessingRecord()
        ->create();

    expect($contactPerson->avgProcessorProcessingRecords()->first())
        ->toBeInstanceOf(AvgProcessorProcessingRecord::class);
});

it('has avg responsible processing records', function (): void {
    $contactPerson = ContactPerson::factory()
        ->withAvgResponsibleProcessingRecord()
        ->create();

    expect($contactPerson->avgResponsibleProcessingRecords()->first())
        ->toBeInstanceOf(AvgResponsibleProcessingRecord::class);
});

it('has wpg processing records', function (): void {
    $contactPerson = ContactPerson::factory()
        ->withWpgProcessingRecord()
        ->create();

    expect($contactPerson->wpgProcessingRecords()->first())
        ->toBeInstanceOf(WpgProcessingRecord::class);
});
