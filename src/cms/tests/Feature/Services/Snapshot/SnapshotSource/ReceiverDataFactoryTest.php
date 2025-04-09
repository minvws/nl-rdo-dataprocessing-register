<?php

declare(strict_types=1);

use App\Models\Receiver;
use App\Models\Snapshot;
use App\Services\Snapshot\SnapshotSource\ReceiverDataFactory;

it('can generate private markdown', function (): void {
    $snapshot = Snapshot::factory()
        ->create();

    $receiverDataFactory = new ReceiverDataFactory();
    expect($receiverDataFactory->generatePrivateMarkdown($snapshot))
        ->toBeNull();
});

it('can generate public frontmatter', function (): void {
    $snapshot = Snapshot::factory()
        ->create();

    $receiverDataFactory = new ReceiverDataFactory();
    expect($receiverDataFactory->generatePublicFrontmatter($snapshot))
        ->toBe([]);
});

it('can generate public markdown', function (): void {
    $receiver = Receiver::factory()
        ->create([
            'description' => 'Voluptas magni commodi omnis dolor facilis sed.',
        ]);
    $snapshot = Snapshot::factory()
        ->for($receiver, 'snapshotSource')
        ->create();

    $receiverDataFactory = new ReceiverDataFactory();
    expect($receiverDataFactory->generatePublicMarkdown($snapshot))
        ->toMatchSnapshot();
});
