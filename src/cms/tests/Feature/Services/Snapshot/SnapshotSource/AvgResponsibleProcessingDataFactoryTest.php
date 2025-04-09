<?php

declare(strict_types=1);

use App\Enums\SitemapType;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\EntityNumber;
use App\Models\Snapshot;
use App\Services\Snapshot\SnapshotSource\AvgResponsibleProcessingRecordDataFactory;

it('can generate private markdown', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'review_at' => '2001-07-21',
            'entity_number_id' => EntityNumber::factory(state: [
                'number' => '7cb7c7db-6e97-3391-8153-477df1e8fde8',
            ]),
            'import_id' => null,
            'created_at' => '2024-04-18 12:18',
            'updated_at' => '2024-04-18 12:18',
            'responsibility_distribution' => 'Et dicta illo suscipit sint sunt accusamus.',
            'has_security' => true,
            'has_pseudonymization' => true,
            'pseudonymization' => 'Aut porro et nulla.',
            'geb_dpia_executed' => 'yes',
            'public_from' => '2012-05-24',
        ]);
    $snapshot = Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->create();

    $avgResponsibleProcessingRecordDataFactory = new AvgResponsibleProcessingRecordDataFactory();
    expect($avgResponsibleProcessingRecordDataFactory->generatePrivateMarkdown($snapshot))
        ->toMatchSnapshot();
});

it('can generate public frontmatter', function (): void {
    $name = fake()->uuid();
    $number = fake()->uuid();

    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'name' => $name,
            'entity_number_id' => EntityNumber::factory(state: [
                'number' => $number,
            ]),
        ]);
    $snapshot = Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->create();

    $avgResponsibleProcessingRecordDataFactory = new AvgResponsibleProcessingRecordDataFactory();
    expect($avgResponsibleProcessingRecordDataFactory->generatePublicFrontmatter($snapshot))
        ->toBe([
            'title' => $name,
            'type' => SitemapType::PROCESSING_RECORD,
            'record' => [
                'reference' => $number,
                'title' => $name,
                'description' => '',
            ],
        ]);
});

it('can generate public markdown', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'name' => 'e8ef4656-8534-31f7-b4d0-7852660f5c49',
            'decision_making' => true,
            'outside_eu' => false,
        ]);
    $snapshot = Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->create();

    $avgResponsibleProcessingRecordDataFactory = new AvgResponsibleProcessingRecordDataFactory();
    expect($avgResponsibleProcessingRecordDataFactory->generatePublicMarkdown($snapshot))
        ->toMatchSnapshot();
});
