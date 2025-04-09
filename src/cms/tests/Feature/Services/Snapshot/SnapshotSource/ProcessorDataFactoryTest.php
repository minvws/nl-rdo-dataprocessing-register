<?php

declare(strict_types=1);

use App\Models\Processor;
use App\Models\Snapshot;
use App\Services\Snapshot\SnapshotSource\ProcessorDataFactory;

it('can generate private markdown', function (): void {
    $processor = Processor::factory()
        ->create([
            'name' => '900666e5-dfea-38a1-b2dd-be62782ed1a7',
            'email' => 'emir45@example.net',
        ]);
    $snapshot = Snapshot::factory()
        ->for($processor, 'snapshotSource')
        ->create();

    $processorDataFactory = new ProcessorDataFactory();
    expect($processorDataFactory->generatePrivateMarkdown($snapshot))
        ->toMatchSnapshot();
});

it('can generate public frontmatter', function (): void {
    $snapshot = Snapshot::factory()
        ->create();

    $processorDataFactory = new ProcessorDataFactory();
    expect($processorDataFactory->generatePublicFrontmatter($snapshot))
        ->toBe([]);
});

it('can generate public markdown', function (): void {
    $snapshot = Snapshot::factory()
        ->create();

    $processorDataFactory = new ProcessorDataFactory();
    expect($processorDataFactory->generatePublicMarkdown($snapshot))
        ->toBeNull();
});
