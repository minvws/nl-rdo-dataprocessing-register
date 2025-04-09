<?php

declare(strict_types=1);

use App\Models\Snapshot;
use App\Models\System;
use App\Services\Snapshot\SnapshotSource\SystemDataFactory;

it('can generate private markdown', function (): void {
    $system = System::factory()
        ->create([
            'description' => 'Commodi aliquam autem mollitia ad numquam omnis maiores accusantium.',
        ]);
    $snapshot = Snapshot::factory()
        ->for($system, 'snapshotSource')
        ->create();

    $systemDataFactory = new SystemDataFactory();
    expect($systemDataFactory->generatePrivateMarkdown($snapshot))
        ->toMatchSnapshot();
});

it('can generate public frontmatter', function (): void {
    $snapshot = Snapshot::factory()
        ->create();

    $systemDataFactory = new SystemDataFactory();
    expect($systemDataFactory->generatePublicFrontmatter($snapshot))
        ->toBe([]);
});

it('can generate public markdown', function (): void {
    $snapshot = Snapshot::factory()
        ->create();

    $systemDataFactory = new SystemDataFactory();
    expect($systemDataFactory->generatePublicMarkdown($snapshot))
        ->toBeNull();
});
