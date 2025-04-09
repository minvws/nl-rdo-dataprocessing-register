<?php

declare(strict_types=1);

use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Snapshot;
use App\Services\Snapshot\SnapshotSource\AvgProcessorProcessingRecordDataFactory;

it('can generate private markdown', function (): void {
    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::factory()
        ->create([
            'name' => '8a354023-7c12-3555-92bf-3895cf6c5704',
            'decision_making' => true,
            'outside_eu' => false,
        ]);
    $snapshot = Snapshot::factory()
        ->for($avgProcessorProcessingRecord, 'snapshotSource')
        ->create();

    $avgProcessorProcessingRecordDataFactory = new AvgProcessorProcessingRecordDataFactory();
    expect($avgProcessorProcessingRecordDataFactory->generatePrivateMarkdown($snapshot))
        ->toMatchSnapshot();
});

it('can generate public frontmatter', function (): void {
    $snapshot = Snapshot::factory()
        ->create();

    $avgProcessorProcessingRecordDataFactory = new AvgProcessorProcessingRecordDataFactory();
    expect($avgProcessorProcessingRecordDataFactory->generatePublicFrontmatter($snapshot))
        ->toBe([]);
});

it('can generate public markdown', function (): void {
    $snapshot = Snapshot::factory()
        ->create();

    $avgProcessorProcessingRecordDataFactory = new AvgProcessorProcessingRecordDataFactory();
    expect($avgProcessorProcessingRecordDataFactory->generatePublicMarkdown($snapshot))
        ->toBeNull();
});
