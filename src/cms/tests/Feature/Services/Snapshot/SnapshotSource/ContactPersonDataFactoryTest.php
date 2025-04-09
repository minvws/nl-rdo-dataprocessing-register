<?php

declare(strict_types=1);

use App\Models\ContactPerson;
use App\Models\Snapshot;
use App\Services\Snapshot\SnapshotSource\ContactPersonDataFactory;

it('can generate private markdown', function (): void {
    $contactPerson = ContactPerson::factory()
        ->create([
            'name' => 'b120ec80-49ab-37fe-a67a-0fc988a30950',
            'email' => 'boer.amin@example.org',
        ]);
    $snapshot = Snapshot::factory()
        ->for($contactPerson, 'snapshotSource')
        ->create();

    $contactPersonDataFactory = new ContactPersonDataFactory();
    expect($contactPersonDataFactory->generatePrivateMarkdown($snapshot))
        ->toMatchSnapshot();
});

it('can generate public frontmatter', function (): void {
    $snapshot = Snapshot::factory()
        ->create();

    $contactPersonDataFactory = new ContactPersonDataFactory();
    expect($contactPersonDataFactory->generatePublicFrontmatter($snapshot))
        ->toBe([]);
});

it('can generate public markdown', function (): void {
    $snapshot = Snapshot::factory()
        ->create();

    $contactPersonDataFactory = new ContactPersonDataFactory();
    expect($contactPersonDataFactory->generatePublicMarkdown($snapshot))
        ->toBeNull();
});
