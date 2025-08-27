<?php

declare(strict_types=1);

use App\Filament\Resources\AvgProcessorProcessingRecordResource;
use App\Import\Factories\Avg\AvgProcessorProcessingRecordFactory;
use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Snapshot;
use App\Models\States\Snapshot\Approved;
use App\Models\States\Snapshot\Established;
use App\Models\States\Snapshot\InReview;
use App\Models\States\Snapshot\Obsolete;
use Carbon\CarbonImmutable;
use Tests\Helpers\ConfigTestHelper;
use Tests\Helpers\Model\OrganisationTestHelper;

it('imports the model', function (): void {
    $importId = fake()->slug();
    $data = getAvgProcessorProcessingRecordFactoryImportData($importId);

    $organisation = OrganisationTestHelper::create();
    $avgProcessorProcessingRecordCount = AvgProcessorProcessingRecord::query()
        ->where(['import_id' => $importId])
        ->count();

    expect($avgProcessorProcessingRecordCount)
        ->toBe(0);

    $avgProcessorProcessingRecordFactory = $this->app->get(AvgProcessorProcessingRecordFactory::class);
    $avgResponsibleProcessingRecord = $avgProcessorProcessingRecordFactory->create($data, $organisation->id);

    $avgProcessorProcessingRecordCount = AvgProcessorProcessingRecord::query()
        ->where(['import_id' => $importId])
        ->count();

    expect($avgProcessorProcessingRecordCount)
        ->toBe(1);

    $this->asFilamentOrganisationUser($organisation)
        ->get(AvgProcessorProcessingRecordResource::getUrl('edit', [
            'tenant' => $organisation,
            'record' => $avgResponsibleProcessingRecord,
        ]))
        ->assertOk();
});

it('skips the import when model with import_id exists', function (): void {
    $importId = fake()->importId();
    $name = fake()->word();

    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::factory()
        ->create([
            'import_id' => $importId,
            'name' => $name,
        ]);

    $avgProcessorProcessingRecordFactory = $this->app->get(AvgProcessorProcessingRecordFactory::class);
    $avgProcessorProcessingRecordFactory->create([
        'Id' => $importId, // same import_id
        'Naam' => fake()->unique()->word(), // new value for the name
    ], $avgProcessorProcessingRecord->organisation_id);

    $avgProcessorProcessingRecord->refresh();
    expect($avgProcessorProcessingRecord->name)
        ->toBe($name);
});

it('skips the import when model with specified state to skip', function (): void {
    $organisation = OrganisationTestHelper::create();
    $importId = fake()->slug();
    $status = fake()->word();

    ConfigTestHelper::set('import.states_to_skip_import', [$status]);

    $data = getAvgProcessorProcessingRecordFactoryImportData($importId);
    $data['Status'] = $status;

    $avgProcessorProcessingRecordFactory = $this->app->get(AvgProcessorProcessingRecordFactory::class);
    $avgProcessorProcessingRecordFactory->create($data, $organisation->id);

    $this->assertDatabaseMissing(AvgProcessorProcessingRecord::class, [
        'import_id' => $importId,
    ]);
});

it('imports the measures & description correctly', function (): void {
    $organisation = OrganisationTestHelper::create();
    $importId = fake()->slug();
    $data = getAvgProcessorProcessingRecordFactoryImportData($importId);
    $data['Beveiliging'] = [
        'Pseudonimisering' => 'De Dienst Publiek en Communicatie van het ministerie van Algemene Zaken',
        'Encryptie' => 'De gegevensopslag is versleuteld.',
        'ElectronischeWeg' => ['Nee'],
        'Toegang' => [
            'Personeel dat onder leiding van de verantwoordelijke staat',
            'Personeel dat onder leiding van de verwerker staat',
            'Derden: Personeel dat onder leiding van de verantwoordelijke staat: beheerders van de communities...',
        ],
        'Verwerkers' => 'Ja',
        'Verantwoordelijken' => 'Minister',
        'Maatregelen' => [
            'Inbraakalarm',
            'Overige beveiligingsmaatregelen: Slechts een beperkt aantal mensen heeft toegang',
        ],
        'HasBeveiliging' => true,
    ];

    $avgProcessorProcessingRecordFactory = $this->app->get(AvgProcessorProcessingRecordFactory::class);
    $avgProcessorProcessingRecordFactory->create($data, $organisation->id);

    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::query()
        ->where(['import_id' => $importId])
        ->firstOrFail();

    expect($avgProcessorProcessingRecord->other_measures)
        ->toBeTrue()
        ->and($avgProcessorProcessingRecord->measures_description)
        ->toBe("Encryptie: De gegevensopslag is versleuteld.
ElectronischeWeg: Nee
Toegang: Personeel dat onder leiding van de verantwoordelijke staat
Personeel dat onder leiding van de verwerker staat
Derden: Personeel dat onder leiding van de verantwoordelijke staat: beheerders van de communities...
Verwerkers: Ja
Verantwoordelijken: Minister
Maatregelen: Inbraakalarm
Overige beveiligingsmaatregelen: Slechts een beperkt aantal mensen heeft toegang");
});

it('imports the measures & description correctly even when values for Beveiliging are null', function (): void {
    $organisation = OrganisationTestHelper::create();
    $importId = fake()->slug();
    $data = getAvgProcessorProcessingRecordFactoryImportData($importId);
    $data['Beveiliging'] = [
        'Pseudonimisering' => null,
        'Encryptie' => null,
        'ElectronischeWeg' => null,
        'Toegang' => null,
        'Verwerkers' => null,
        'Verantwoordelijken' => null,
        'Maatregelen' => null,
        'HasBeveiliging' => null,
    ];

    $avgProcessorProcessingRecordFactory = $this->app->get(AvgProcessorProcessingRecordFactory::class);
    $avgProcessorProcessingRecordFactory->create($data, $organisation->id);

    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::query()
        ->where(['import_id' => $importId])
        ->firstOrFail();

    expect($avgProcessorProcessingRecord->other_measures)
        ->toBeFalse()
        ->and($avgProcessorProcessingRecord->measures_description)
        ->toBeNull();
});

it('can import the model & snapshot', function (): void {
    $status = 'Vastgesteld';
    $importId = fake()->slug();

    ConfigTestHelper::set(sprintf('import.value_converters.snapshot_state.%s', $status), Approved::class);

    $organisation = OrganisationTestHelper::create();
    $data = getAvgProcessorProcessingRecordFactoryImportData($importId);
    $data['Status'] = $status;
    $data['Versie'] = fake()->randomDigit();

    $avgProcessorProcessingRecordFactory = $this->app->get(AvgProcessorProcessingRecordFactory::class);
    $avgProcessorProcessingRecordFactory->create($data, $organisation->id);

    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::query()
        ->where(['import_id' => $importId])
        ->firstOrFail();
    $this->assertDatabaseHas(Snapshot::class, [
        'snapshot_source_id' => $avgProcessorProcessingRecord->id,
        'snapshot_source_type' => AvgProcessorProcessingRecord::class,
    ]);
});

it('will set the review_at based on LaatsteWijzigDatum if snapshot-state is established', function (): void {
    $status = fake()->randomElement([
        'TerReview',
        'VaststellingAangevraagd',
        'Vastgesteld',
    ]);
    $lastChangeDate = fake()->dateTime()->format('d-m-Y');

    ConfigTestHelper::set(sprintf('import.value_converters.snapshot_state.%s', $status), Established::class);
    ConfigTestHelper::set('import.date.expectedFormats', ['Y-m-d\TH:i:s.v', 'd-m-Y']);

    $reviewAtDefaultInMonths = fake()->numberBetween(0, 10);
    $organisation = OrganisationTestHelper::create([
        'review_at_default_in_months' => $reviewAtDefaultInMonths,
    ]);
    $importId = fake()->slug();
    $data = getAvgProcessorProcessingRecordFactoryImportData($importId);
    $data['Status'] = $status;
    $data['Versie'] = fake()->randomDigit();
    $data['LaatsteWijzigDatum'] = $lastChangeDate;

    $avgProcessorProcessingRecordFactory = $this->app->get(AvgProcessorProcessingRecordFactory::class);
    $avgProcessorProcessingRecordFactory->create($data, $organisation->id);

    $this->assertDatabaseHas(AvgProcessorProcessingRecord::class, [
        'import_id' => $importId,
        'review_at' => CarbonImmutable::createFromFormat('d-m-Y', $lastChangeDate)->addMonths($reviewAtDefaultInMonths),
    ]);
});

it('will not set the review_at if snapshot-state is not approved', function (): void {
    $status = fake()->randomElement([
        'TerReview',
        'VaststellingAangevraagd',
        'Vastgesteld',
    ]);
    $lastChangeDate = fake()->dateTime()->format('d-m-Y');

    ConfigTestHelper::set(sprintf('import.value_converters.snapshot_state.%s', $status), fake()->randomElement([
        InReview::class,
        Approved::class,
        Obsolete::class,
    ]));
    ConfigTestHelper::set('import.date.expectedFormats', ['Y-m-d\TH:i:s.v', 'd-m-Y']);

    $reviewAtDefaultInMonths = fake()->numberBetween(0, 10);
    $organisation = OrganisationTestHelper::create([
        'review_at_default_in_months' => $reviewAtDefaultInMonths,
    ]);
    $importId = fake()->slug();
    $data = getAvgProcessorProcessingRecordFactoryImportData($importId);
    $data['Status'] = $status;
    $data['Versie'] = fake()->randomDigit();
    $data['LaatsteWijzigDatum'] = $lastChangeDate;

    $avgProcessorProcessingRecordFactory = $this->app->get(AvgProcessorProcessingRecordFactory::class);
    $avgProcessorProcessingRecordFactory->create($data, $organisation->id);

    $this->assertDatabaseHas(AvgProcessorProcessingRecord::class, [
        'import_id' => $importId,
        'review_at' => null,
    ]);
});

function getAvgProcessorProcessingRecordFactoryImportData(string $importId): array
{
    return [
        'Id' => $importId,
        'AanmaakDatum' => fake()->date('Y-m-d\TH:i:s.v'),
        'LaatsteWijzigDatum' => fake()->date('Y-m-d\TH:i:s.v'),
        'Naam' => fake()->word(),
        'Nummer' => fake()->word(),
        'Dienst' => fake()->word(),
        'VerdelingVerantwoordelijkheid' => fake()->word(),
        'Opmerkingen' => [
            ['Datum' => fake()->dateTime()->format('d-m-Y H:i'), 'Tekst' => fake()->word()],
        ],
        'Beveiliging' => [
            'Pseudonimisering' => fake()->word(),
            'Encryptie' => fake()->word(),
            'ElectronischeWeg' => [fake()->word()],
            'Toegang' => [fake()->word()],
            'Verwerkers' => fake()->word(),
            'Verantwoordelijken' => fake()->word(),
            'Maatregelen' => [fake()->word()],
            'HasBeveiliging' => fake()->boolean(),
        ],
        'Doorgifte' => [
            'BuitenEu' => fake()->boolean(),
            'BuitenEuOmschrijving' => fake()->word(),
            'BuitenEuPassendBeschermingsniveau' => fake()->boolean(),
            'BuitenEuPassendBeschermingsniveauOmschrijving' => fake()->word(),
        ],
        'Besluitvorming' => [
            'HasBesluitvorming' => fake()->boolean(),
            'Logica' => fake()->word(),
            'BelangGevolgen' => fake()->word(),
        ],
        'GebPia' => [
            'Uitgevoerd' => fake()->boolean(),
        ],
        'Doelen' => [
            [
                'Id' => $importId,
                'Doel' => fake()->word(),
                'Rechtsgrond' => fake()->word(),
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
        'Categorien' => [
            [
                'Id' => $importId,
                'Omschrijving' => fake()->word(),
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
        'Status' => fake()->word(),
    ];
}
