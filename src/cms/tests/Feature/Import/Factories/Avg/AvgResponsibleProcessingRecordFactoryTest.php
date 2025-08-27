<?php

declare(strict_types=1);

use App\Components\Uuid\Uuid;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource;
use App\Import\Factories\Avg\AvgResponsibleProcessingRecordFactory;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\ContactPerson;
use App\Models\Snapshot;
use App\Models\States\Snapshot\Approved;
use App\Models\States\Snapshot\Established;
use App\Models\States\Snapshot\InReview;
use App\Models\States\Snapshot\Obsolete;
use App\ValueObjects\CalendarDate;
use Carbon\CarbonImmutable;
use Tests\Helpers\ConfigTestHelper;
use Tests\Helpers\Model\OrganisationTestHelper;

it('imports the model & related models, with snapshots in the correct state', function (): void {
    $name = fake()->word();
    $importId = fake()->slug(1);
    $status = fake()->word();
//    $lastChangeDate = fake()->date('Y-m-d\TH:i:s.v');
    $lastChangeDate = '1990-01-08T00:50:41.000';

    ConfigTestHelper::set('import.value_converters.snapshot_state', [$status => Established::class]);

    $organisation = OrganisationTestHelper::create();
    $avgResponsibleProcessingRecordCount = AvgResponsibleProcessingRecord::query()
        ->where(['import_id' => $importId])
        ->count();

    expect($avgResponsibleProcessingRecordCount)
        ->toBe(0);

    $data = getAvgResponsibleProcessingRecordFactoryImportData($importId, $name, $status, $lastChangeDate);

    $avgResponsibleProcessingRecordFactory = $this->app->get(AvgResponsibleProcessingRecordFactory::class);
    $avgResponsibleProcessingRecord = $avgResponsibleProcessingRecordFactory->create($data, $organisation->id);

    $avgResponsibleProcessingRecordCount = AvgResponsibleProcessingRecord::query()
        ->where(['import_id' => $importId])
        ->count();
    $expectedReviewAt = CalendarDate::parse($lastChangeDate)
        ->addMonths($organisation->review_at_default_in_months);

    $avgResponsibleProcessingRecordSnapshots = $avgResponsibleProcessingRecord->snapshots;
    $avgResponsibleProcessingRecordContacts = $avgResponsibleProcessingRecord->contactPersons();
    $avgResponsibleProcessingRecordContactSnapshots = $avgResponsibleProcessingRecordContacts->first()->snapshots;

    expect($avgResponsibleProcessingRecordCount)->toBe(1)
        ->and($avgResponsibleProcessingRecord->review_at->equalTo($expectedReviewAt))->toBeTrue()
        ->and($avgResponsibleProcessingRecordSnapshots->count())->toBe(1)
        ->and($avgResponsibleProcessingRecordSnapshots->first()->state)->toBeInstanceOf(Established::class)
        ->and($avgResponsibleProcessingRecordContacts->count())->toBe(1)
        ->and($avgResponsibleProcessingRecordContactSnapshots->count())->toBe(1)
        ->and($avgResponsibleProcessingRecordContactSnapshots->first()->state)->toBeInstanceOf(Established::class);

    $this->asFilamentOrganisationUser($organisation)
        ->get(AvgResponsibleProcessingRecordResource::getUrl('edit', [
            'tenant' => $organisation,
            'record' => $avgResponsibleProcessingRecord,
        ]))
        ->assertOk();
});

it('skips the import when model with specified state to skip', function (): void {
    $organisation = OrganisationTestHelper::create();
    $importId = fake()->slug();
    $status = fake()->word();
    $lastChangeDate = fake()->date('Y-m-d\TH:i:s.v');

    ConfigTestHelper::set('import.states_to_skip_import', [$status]);

    $data = getAvgResponsibleProcessingRecordFactoryImportData($importId, fake()->word(), $status, $lastChangeDate);
    $data['Status'] = $status;

    $avgResponsibleProcessingRecordFactory = $this->app->get(AvgResponsibleProcessingRecordFactory::class);
    $avgResponsibleProcessingRecordFactory->create($data, $organisation->id);

    $this->assertDatabaseMissing(AvgResponsibleProcessingRecord::class, [
        'import_id' => $importId,
    ]);
});

it('imports the model & related models, but does not create a new snapshot for the related entity', function (): void {
    $name = fake()->word();
    $importId = fake()->slug(1);
    $status = fake()->word();
    $contactPersonSnapshotId = Uuid::fromString(fake()->uuid());
    $lastChangeDate = fake()->date('Y-m-d\TH:i:s.v');

    ConfigTestHelper::set('import.value_converters.snapshot_state', [$status => Established::class]);

    $organisation = OrganisationTestHelper::create();
    $contactPerson = ContactPerson::factory()
        ->recycle($organisation)
        ->create(['import_id' => $importId]);
    Snapshot::factory()
        ->recycle($organisation)
        ->for($contactPerson, 'snapshotSource')
        ->create([
            'id' => $contactPersonSnapshotId,
            'state' => Established::class,
        ]);

    $data = getAvgResponsibleProcessingRecordFactoryImportData($importId, $name, $status, $lastChangeDate);

    $avgResponsibleProcessingRecordFactory = $this->app->get(AvgResponsibleProcessingRecordFactory::class);
    $avgResponsibleProcessingRecord = $avgResponsibleProcessingRecordFactory->create($data, $organisation->id);

    $avgResponsibleProcessingRecordContactSnapshots = $avgResponsibleProcessingRecord->contactPersons->first()->snapshots;

    expect($avgResponsibleProcessingRecordContactSnapshots->count())->toBe(1)
        ->and($avgResponsibleProcessingRecordContactSnapshots->first()->id)->toBe($contactPersonSnapshotId);
});

it('imports the model & related models, and creates a new snapshot for the related entity with the new state', function (): void {
    $name = fake()->word();
    $importId = fake()->slug(1);
    $status = fake()->word();
    $lastChangeDate = fake()->date('Y-m-d\TH:i:s.v');

    ConfigTestHelper::set('import.value_converters.snapshot_state', [$status => Established::class]);

    $organisation = OrganisationTestHelper::create();
    $contactPerson = ContactPerson::factory()
        ->recycle($organisation)
        ->create(['import_id' => $importId]);
    Snapshot::factory()
        ->recycle($organisation)
        ->for($contactPerson, 'snapshotSource')
        ->create([
            'state' => Approved::class,
        ]);

    $data = getAvgResponsibleProcessingRecordFactoryImportData($importId, $name, $status, $lastChangeDate);

    $avgResponsibleProcessingRecordFactory = $this->app->get(AvgResponsibleProcessingRecordFactory::class);
    $avgResponsibleProcessingRecord = $avgResponsibleProcessingRecordFactory->create($data, $organisation->id);

    $contactPersonSnapshots = $avgResponsibleProcessingRecord->contactPersons
        ->first()
        ->snapshots()
        ->orderByVersion()
        ->get();

    expect($contactPersonSnapshots->count())->toBe(2)
        ->and($contactPersonSnapshots->first()->state)->toBeInstanceOf(Established::class);
});

it('imports the model & related models, and creates a new snapshot for the related entity with the correct state', function (
    string $importState,
    string $relatedEntitySnapshotState,
): void {
    $importId = fake()->slug(1);
    $status = fake()->word();
    $lastChangeDate = fake()->date('Y-m-d\TH:i:s.v');

    ConfigTestHelper::set('import.value_converters.snapshot_state', [$status => $importState]);

    $organisation = OrganisationTestHelper::create();
    $contactPerson = ContactPerson::factory()
        ->recycle($organisation)
        ->create(['import_id' => $importId]);
    Snapshot::factory()
        ->recycle($organisation)
        ->for($contactPerson, 'snapshotSource')
        ->create([
            'state' => $relatedEntitySnapshotState,
            'created_at' => CarbonImmutable::now()->subDay(),
        ]);

    $data = getAvgResponsibleProcessingRecordFactoryImportData($importId, fake()->word(), $status, $lastChangeDate);

    $avgResponsibleProcessingRecordFactory = $this->app->get(AvgResponsibleProcessingRecordFactory::class);
    $avgResponsibleProcessingRecord = $avgResponsibleProcessingRecordFactory->create($data, $organisation->id);

    $contactPersonSnapshots = $avgResponsibleProcessingRecord->contactPersons
        ->first()
        ->snapshots()
        ->orderByVersion()
        ->get();

    expect($contactPersonSnapshots->count())->toBe(2)
        ->and($contactPersonSnapshots->first()->state)->toBeInstanceOf($importState);
})->with([
    [Established::class, Approved::class],
    [Established::class, InReview::class],
    [Established::class, Obsolete::class],
    [Approved::class, InReview::class],
    [Approved::class, Obsolete::class],
    [InReview::class, Obsolete::class],
]);

it('skips the import when model with import_id exists', function (): void {
    $importId = fake()->importId();
    $name = fake()->word();

    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'import_id' => $importId,
            'name' => $name,
        ]);

    $avgResponsibleProcessingRecordFactory = $this->app->get(AvgResponsibleProcessingRecordFactory::class);
    $avgResponsibleProcessingRecordFactory->create([
        'Id' => $importId, // same import_id
        'Naam' => fake()->unique()->word(), // new value for the name
    ], $avgResponsibleProcessingRecord->organisation_id);

    $avgResponsibleProcessingRecord->refresh();
    expect($avgResponsibleProcessingRecord->name)->toBe($name);
});

function getAvgResponsibleProcessingRecordFactoryImportData(
    string $importId,
    string $name,
    string $status,
    string $lastChangeDate,
): array {
    return [
        'Id' => $importId,
        'AanmaakDatum' => fake()->date('Y-m-d\TH:i:s.v'),
        'LaatsteWijzigDatum' => $lastChangeDate,
        'Naam' => $name,
        'Nummer' => fake()->word(),
        'Dienst' => fake()->word(),
        'VerdelingVerantwoordelijkheid' => fake()->word(),
        'Opmerkingen' => [
            ['Datum' => fake()->dateTime()->format('d-m-Y H:i'), 'Tekst' => fake()->word()],
        ],
        'Beveiliging' => [
            'Pseudonimisering' => fake()->word(),
            'Encryptie' => fake()->word(),
            'ElectronischeWeg' => [
                fake()->word(),
                fake()->word(),
                fake()->word(),
            ],
            'Toegang' => [
                fake()->word(),
                fake()->word(),
                fake()->word(),
            ],
            'Verwerkers' => fake()->word(),
            'Verantwoordelijken' => fake()->word(),
            'Maatregelen' => [
                fake()->word(),
                fake()->word(),
                fake()->word(),
            ],
            'HasBeveiliging' => fake()->boolean(),
        ],
        'Doorgifte' => [
            'BuitenEu' => fake()->boolean(),
            'BuitenEuOmschrijving' => fake()->word(),
            'BuitenEuPassendBeschermingsniveau' => fake()->optional()->yesNoUnknown()?->value,
            'BuitenEuPassendBeschermingsniveauOmschrijving' => fake()->optional()->word(),
        ],
        'Besluitvorming' => [
            'HasBesluitvorming' => fake()->boolean(),
            'Logica' => fake()->optional()->word(),
            'BelangGevolgen' => fake()->optional()->word(),
        ],
        'GebPia' => [
            'IsGebpiaAlUitgevoerd' => fake()->optional()->yesNoUnknown()?->value,
            'IsGeautomatiseerdProfilering' => fake()->optional()->yesNoUnknown()?->value,
            'IsGrootschaligeVerwerking' => fake()->optional()->yesNoUnknown()?->value,
            'IsGrootschaligeMonitoring' => fake()->optional()->yesNoUnknown()?->value,
            'StaatOpLijstVerplichteGebpia' => fake()->optional()->yesNoUnknown()?->value,
            'VoeldoetAanTweeCriteriaWP248' => fake()->optional()->yesNoUnknown()?->value,
            'IsHoogRisicoRechtenVrijheden' => fake()->optional()->yesNoUnknown()?->value,
        ],
        'Doelen' => [
            [
                'Id' => $importId,
                'Doel' => fake()->word(),
                'Rechtsgrond' => fake()->word(),
            ],
        ],
        'Betrokkenen' => [
            [
                'Id' => $importId,
                'Omschrijving' => fake()->text(),
                'GeenBijzondereGegevens' => fake()->boolean(),
                'RasOfEtniciteit' => fake()->boolean(),
                'PolitiekeGezindheid' => fake()->boolean(),
                'GodsdienstOfLevensovertuiging' => fake()->boolean(),
                'LidmaatschapVakvereniging' => fake()->boolean(),
                'Genetisch' => fake()->boolean(),
                'Biometrisch' => fake()->boolean(),
                'Gezondheid' => fake()->boolean(),
                'SeksueleLeven' => fake()->boolean(),
                'Strafrechtelijk' => fake()->boolean(),
                'Gegevens' => [
                    [
                        'Id' => $importId,
                        'Omschrijving' => fake()->text(),
                        'Verzameldoel' => fake()->text(),
                        'Bewaartermijn' => fake()->text(),
                        'IsBronBetrokkene' => fake()->boolean(),
                        'BronOmschrijving' => fake()->text(),
                        'IsBetrokkeneVerplicht' => fake()->boolean(),
                        'BetrokkeneConsequenties' => fake()->text(),
                    ],
                ],
            ],
        ],
        'Contactpersonen' => [
            [
                'Id' => $importId,
                'Naam' => fake()->word(),
                'Functie' => fake()->word(),
                'Email' => fake()->word(),
                'Telefoon' => fake()->word(),
                'Adres' => fake()->word(),
                'Postcode' => fake()->word(),
                'Plaats' => fake()->word(),
                'Land' => fake()->word(),
                'Postbus' => fake()->word(),
                'PostbusPostcode' => fake()->word(),
                'PostbusPlaats' => fake()->word(),
                'PostbusLand' => fake()->word(),
            ],
        ],
        'Verwerkers' => [
            [
                'Id' => $importId,
                'Naam' => fake()->word(),
                'Type' => fake()->word(),
                'Email' => fake()->word(),
                'Telefoon' => fake()->word(),
                'Adres' => fake()->word(),
                'Postcode' => fake()->word(),
                'Plaats' => fake()->word(),
                'Land' => fake()->word(),
                'Postbus' => fake()->word(),
                'PostbusPostcode' => fake()->word(),
                'PostbusPlaats' => fake()->word(),
                'PostbusLand' => fake()->word(),
            ],
        ],
        'Ontvangers' => [
            [
                'Id' => $importId,
                'Omschrijving' => fake()->word(),
            ],
        ],
        'Verantwoordelijken' => [
            [
                'Id' => $importId,
                'Naam' => fake()->word(),
                'Type' => fake()->word(),
                'Email' => fake()->word(),
                'Telefoon' => fake()->word(),
                'Adres' => fake()->word(),
                'Postcode' => fake()->word(),
                'Plaats' => fake()->word(),
                'Land' => fake()->word(),
                'Postbus' => fake()->word(),
                'PostbusPostcode' => fake()->word(),
                'PostbusPlaats' => fake()->word(),
                'PostbusLand' => fake()->word(),
            ],
        ],
        'Systemen' => [
            [
                'Id' => $importId,
                'Omschrijving' => fake()->name(),
            ],
        ],
        'Versie' => fake()->randomDigitNotNull(),
        'Status' => $status,
    ];
}
