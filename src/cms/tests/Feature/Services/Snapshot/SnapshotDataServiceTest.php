<?php

declare(strict_types=1);

use App\Enums\CoreEntityDataCollectionSource;
use App\Models\Address;
use App\Models\Avg\AvgGoal;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecordService;
use App\Models\EntityNumber;
use App\Models\Organisation;
use App\Models\Receiver;
use App\Models\Responsible;
use App\Models\Snapshot;
use App\Models\States\Snapshot\Established;
use App\Models\Wpg\WpgProcessingRecord;
use App\Models\Wpg\WpgProcessingRecordService;
use App\Services\Snapshot\SnapshotDataFactory;
use App\ValueObjects\CalendarDate;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\App;

it('creates new snapshot data for a AvgResponsibleProcessingRecord', function (): void {
    $organisation = Organisation::factory()->create([
        'name' => 'Organisatie X',
    ]);
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create([
            'data_collection_source' => CoreEntityDataCollectionSource::SECONDARY,
            'import_id' => '52333',
            'entity_number_id' => EntityNumber::factory()->state([
                'number' => 'AVGR6018277448',
            ]),
            'name' => 'blanditiis',
            'decision_making' => true,
            'outside_eu' => true,
            'responsibility_distribution' => 'Optio doloribus culpa aspernatur.',
            'logic' => 'Voluptatem voluptatem quis unde accusamus iusto corporis.',
            'importance_consequences' => 'Ab dicta deleniti pariatur perferendis sed fugit magnam qui.',
            'has_security' => true,
            'has_pseudonymization' => true,
            'pseudonymization' => 'Distinctio dolor minus unde illo itaque dolorem expedita.',
            'geb_dpia_executed' => false,
            'geb_dpia_automated' => true,
            'outside_eu_description' => null,
            'outside_eu_protection_level' => false,
            'outside_eu_protection_level_description' => 'Quaerat consectetur repellat iure aut.',
            'created_at' => CarbonImmutable::create(2024, 2, 5),
            'updated_at' => CarbonImmutable::create(2024, 2, 5),
            'review_at' => CalendarDate::createFromFormat('Y-m-d', '2024-2-5'),
            'public_from' => CarbonImmutable::create(2024, 2, 5),

            'avg_responsible_processing_record_service_id' => AvgResponsibleProcessingRecordService::factory()->state([
                'name' => 'Reiciendis rerum voluptas consequatur est animi.',
                'enabled' => true,
            ]),
        ]);

    AvgGoal::factory()
        ->hasAttached($avgResponsibleProcessingRecord)
        ->recycle($organisation)
        ->create([
            'goal' => 'Quos non maiores non.',
            'avg_goal_legal_base' => 'Toestemming betrokkene',
            'remarks' => null,
        ]);

    Receiver::factory()
        ->hasAttached($avgResponsibleProcessingRecord)
        ->recycle($organisation)
        ->create([
            'description' => 'Voluptatem debitis omnis praesentium eum animi iste velit.',
        ]);

    /** @var Responsible $responsible */
    $responsible = Responsible::factory()
        ->hasAttached($avgResponsibleProcessingRecord)
        ->recycle($organisation)
        ->create([
            'name' => 'Luca van Hoevel en van Zwindrecht',
        ]);

    Address::factory()
        ->recycle($organisation)
        ->create([
            'addressable_type' => Responsible::class,
            'addressable_id' => $responsible->id,
            'address' => 'Weylantring 9-0',
            'postal_code' => '2242PK',
            'city' => 'Een-West',
            'country' => 'Georgië',
            'postbox' => 'Wilmonthof 166',
            'postbox_postal_code' => '5839HY',
            'postbox_city' => 'Wageningen',
            'postbox_country' => 'Portugal',
        ]);

    /** @var Snapshot $snapshot */
    $snapshot = Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->recycle($organisation)
        ->create([
            'state' => Established::class,
        ]);

    /** @var SnapshotDataFactory $snapshotDataFactory */
    $snapshotDataFactory = App::make(SnapshotDataFactory::class);
    $snapshotData = $snapshotDataFactory->createDataForSnapshot($snapshot);

    expect($snapshotData->private_markdown)->toMatchSnapshot();
    expect($snapshotData->public_frontmatter)->toMatchSnapshot();
    expect($snapshotData->public_markdown)->toMatchSnapshot();
});

it('creates new snapshot data for a WpgProcessingRecord', function (): void {
    $organisation = Organisation::factory()->create();
    $wpgProcessingRecord = WpgProcessingRecord::factory()
        ->for($organisation)
        ->create([
            'name' => 'dolorem',
            'entity_number_id' => EntityNumber::factory(state: [
                'number' => 'OAFMQBH86V',
            ]),
            'import_id' => null,
            'data_collection_source' => CoreEntityDataCollectionSource::SECONDARY,
            'wpg_processing_record_service_id' => WpgProcessingRecordService::factory()->state([
                'name' => 'Reiciendis rerum voluptas consequatur est animi.',
                'enabled' => true,
            ]),
            'review_at' => '2024-02-05',
            'public_from' => '2024-02-05',
            'has_processors' => false,
            'article_15' => false,
            'article_15_a' => true,
            'article_16' => false,
            'article_17' => true,
            'article_17_a' => false,
            'article_18' => true,
            'article_19' => false,
            'article_20' => true,
            'article_22' => false,
            'article_23' => true,
            'article_24' => false,
            'explanation_available' => null,
            'explanation_provisioning' => 'Quaerat consectetur repellat iure aut.',
            'explanation_transfer' => null,
            'police_race_or_ethnicity' => false,
            'police_political_attitude' => true,
            'police_faith_or_belief' => false,
            'police_association_membership' => true,
            'police_genetic' => false,
            'police_identification' => true,
            'police_health' => false,
            'police_sexual_life' => true,
            'decision_making' => false,
            'logic' => 'Voluptatem voluptatem quis unde accusamus iusto corporis.',
            'consequences' => 'Ab dicta deleniti pariatur perferendis sed fugit magnam qui.',
            'has_systems' => false,
            'has_security' => true,
            'measures_implemented' => false,
            'other_measures' => true,
            'measures_description' => null,
            'has_pseudonymization' => false,
            'pseudonymization' => null,
            'geb_pia' => false,
            'suspects' => false,
            'victims' => true,
            'convicts' => false,
            'police_justice' => true,
            'third_parties' => false,
            'third_party_explanation' => null,
        ]);

    $snapshot = Snapshot::factory()
        ->for($wpgProcessingRecord, 'snapshotSource')
        ->state([
            'organisation_id' => $organisation->id,
        ])->create();

    /** @var SnapshotDataFactory $snapshotDataFactory */
    $snapshotDataFactory = App::make(SnapshotDataFactory::class);
    $snapshotData = $snapshotDataFactory->createDataForSnapshot($snapshot);

    expect($snapshotData->private_markdown)->toMatchSnapshot();
    expect($snapshotData->public_markdown)->toBe(null);
    expect($snapshotData->public_frontmatter)->toBe([]);
});
