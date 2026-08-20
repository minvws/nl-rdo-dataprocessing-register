<?php

declare(strict_types=1);

use App\Enums\CoreEntityDataCollectionSource;
use App\Models\EntityNumber;
use App\Models\Snapshot;
use App\Models\Wpg\WpgGoal;
use App\Models\Wpg\WpgProcessingRecord;
use App\Models\Wpg\WpgProcessingRecordService;
use App\Services\Snapshot\SnapshotSource\WpgProcessingRecordDataFactory;

it('can generate private markdown', function (): void {
    $wpgProcessingRecord = WpgProcessingRecord::factory()
        ->create([
            'entity_number_id' => EntityNumber::factory()->state([
                'number' => '7cb7c7db-6e97-3391-8153-477df1e8fde8',
            ]),
            'name' => '2f341f1c-9e52-330b-a19c-f73db7c85681',
            'import_id' => null,
            'data_collection_source' => CoreEntityDataCollectionSource::SECONDARY,
            'wpg_processing_record_service_id' => WpgProcessingRecordService::factory()->state([
                'name' => 'In modi dolore aspernatur nobis ullam magni minus ipsum.',
                'enabled' => true,
            ]),
            'review_at' => '2001-07-21',
            'public_from' => '2012-05-24',
            'has_processors' => true,
            'article_15' => true,
            'article_15_a' => false,
            'article_16' => true,
            'article_17' => false,
            'article_17_a' => true,
            'article_18' => false,
            'article_19' => true,
            'article_20' => false,
            'article_22' => true,
            'article_23' => false,
            'article_24' => true,
            'explanation_available' => 'Et dicta illo suscipit sint sunt accusamus.',
            'explanation_provisioning' => null,
            'explanation_transfer' => 'Aut porro et nulla.',
            'police_race_or_ethnicity' => true,
            'police_political_attitude' => false,
            'police_faith_or_belief' => true,
            'police_association_membership' => false,
            'police_genetic' => true,
            'police_identification' => false,
            'police_health' => true,
            'police_sexual_life' => false,
            'decision_making' => true,
            'logic' => 'Et dicta illo suscipit sint sunt accusamus.',
            'consequences' => 'Et dicta illo suscipit sint sunt accusamus.',
            'has_systems' => true,
            'has_security' => true,
            'measures_implemented' => true,
            'other_measures' => false,
            'measures_description' => 'Et dicta illo suscipit sint sunt accusamus.',
            'has_pseudonymization' => true,
            'pseudonymization' => 'Aut porro et nulla.',
            'geb_pia' => true,
            'suspects' => true,
            'victims' => false,
            'convicts' => true,
            'police_justice' => false,
            'third_parties' => true,
            'third_party_explanation' => 'Et dicta illo suscipit sint sunt accusamus.',
        ]);
    WpgGoal::factory()
        ->hasAttached($wpgProcessingRecord)
        ->create([
            'description' => 'Velit eius itaque distinctio tempore. Quam commodi a minus cumque placeat voluptatem impedit et.',
            'article_8' => true,
            'article_9' => false,
            'article_10_1a' => true,
            'article_10_1b' => false,
            'article_10_1c' => true,
            'article_12' => false,
            'article_13_1' => true,
            'article_13_2' => false,
            'article_13_3' => true,
            'explanation' => 'Quam commodi a minus cumque placeat voluptatem impedit et.',
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
