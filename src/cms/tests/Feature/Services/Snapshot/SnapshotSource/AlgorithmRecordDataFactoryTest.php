<?php

declare(strict_types=1);

use App\Models\Snapshot;
use App\Services\Snapshot\SnapshotSource\AlgorithmRecordDataFactory;

it('can generate private markdown', function (): void {
    $snapshot = Snapshot::factory()
        ->create();

    $algorithmRecordDataFactory = new AlgorithmRecordDataFactory();
    expect($algorithmRecordDataFactory->generatePrivateMarkdown($snapshot))
        ->toBeNull();
});

it('can generate public frontmatter', function (): void {
    $snapshot = Snapshot::factory()
        ->create();

    $algorithmRecordDataFactory = new AlgorithmRecordDataFactory();
    expect($algorithmRecordDataFactory->generatePublicFrontmatter($snapshot))
        ->toBe([]);
});

it('can generate public markdown', function (): void {
    $snapshot = Snapshot::factory()
        ->create();

    $algorithmRecordDataFactory = new AlgorithmRecordDataFactory();
    expect($algorithmRecordDataFactory->generatePublicMarkdown($snapshot))
        ->toBeNull();
});
