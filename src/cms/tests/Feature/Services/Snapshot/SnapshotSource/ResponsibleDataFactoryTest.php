<?php

declare(strict_types=1);

use App\Models\Address;
use App\Models\Responsible;
use App\Models\Snapshot;
use App\Services\Snapshot\SnapshotSource\ResponsibleDataFactory;

it('can generate private markdown', function (): void {
    $snapshot = Snapshot::factory()
        ->create();

    $responsibleDataFactory = new ResponsibleDataFactory();
    expect($responsibleDataFactory->generatePrivateMarkdown($snapshot))
        ->toBeNull();
});

it('can generate public frontmatter', function (): void {
    $snapshot = Snapshot::factory()
        ->create();

    $responsibleDataFactory = new ResponsibleDataFactory();
    expect($responsibleDataFactory->generatePublicFrontmatter($snapshot))
        ->toBe([]);
});

it('can generate public markdown', function (): void {
    $responsible = Responsible::factory()
        ->create([
            'name' => '45e7bdb1-6c59-3090-81c1-144012593ece',
        ]);
    Address::factory()
        ->for($responsible, 'addressable')
        ->create([
            'address' => 'de Pruyssenaere de la Woestijnedreef 61i',
            'postal_code' => '2623HS',
            'city' => 'De Groeve',
            'country' => 'Kaapverdië',

            'postbox' => 'van de Waterring 86',
            'postbox_postal_code' => '2332KV',
            'postbox_city' => 'Beek en Donk',
            'postbox_country' => 'India',
        ]);

    $snapshot = Snapshot::factory()
        ->for($responsible, 'snapshotSource')
        ->create();

    $responsibleDataFactory = new ResponsibleDataFactory();
    expect($responsibleDataFactory->generatePublicMarkdown($snapshot))
        ->toMatchSnapshot();
});
