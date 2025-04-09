<?php

declare(strict_types=1);

use App\Models\Snapshot;
use App\Models\Wpg\WpgGoal;
use App\Models\Wpg\WpgProcessingRecord;
use App\Services\Snapshot\SnapshotSource\WpgProcessingRecordDataFactory;

it('can generate private markdown', function (): void {
    $wpgProcessingRecord = WpgProcessingRecord::factory()
        ->create([
            'name' => '2f341f1c-9e52-330b-a19c-f73db7c85681',
        ]);
    WpgGoal::factory()
        ->hasAttached($wpgProcessingRecord)
        ->create([
            'description' => 'Velit eius itaque distinctio tempore. Quam commodi a minus cumque placeat voluptatem impedit et.',
        ]);

    $snapshot = Snapshot::factory()
        ->for($wpgProcessingRecord, 'snapshotSource')
        ->create();

    $wpgProcessingRecordDataFactory = new WpgProcessingRecordDataFactory();
    expect($wpgProcessingRecordDataFactory->generatePrivateMarkdown($snapshot))
        ->toMatchSnapshot();
});

it('can generate public frontmatter', function (): void {
    $snapshot = Snapshot::factory()
        ->create();

    $wpgProcessingRecordDataFactory = new WpgProcessingRecordDataFactory();
    expect($wpgProcessingRecordDataFactory->generatePublicFrontmatter($snapshot))
        ->toBe([]);
});

it('can generate public markdown', function (): void {
    $snapshot = Snapshot::factory()
        ->create();

    $wpgProcessingRecordDataFactory = new WpgProcessingRecordDataFactory();
    expect($wpgProcessingRecordDataFactory->generatePublicMarkdown($snapshot))
        ->toBeNull();
});
