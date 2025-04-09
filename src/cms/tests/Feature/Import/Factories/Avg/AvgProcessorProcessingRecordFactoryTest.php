<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Import\Factories\Avg\AvgProcessorProcessingRecordFactory;
use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Organisation;
use App\Models\Snapshot;
use App\Models\States\Snapshot\Approved;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Testing\TestResponse;
use Tests\Helpers\ConfigHelper;

beforeEach(function (): void {
    $user = User::factory()
        ->withOrganisation()
        ->withValidOtpRegistration()
        ->create();
    /** @var Organisation $organisation */
    $organisation = $user->organisations()->firstOrFail();

    $user->assignGlobalRole(Role::FUNCTIONAL_MANAGER);
    $user->assignOrganisationRole(Role::INPUT_PROCESSOR, $organisation);

    $this->be($user);
    Filament::setTenant($organisation);

    $this->organisation = $organisation;
    setOtpValidSessionValue(true);
});

it('imports the model', function (): void {
    $importId = fake()->slug();
    $data = getAvgProcessorProcessingRecordFactoryImportData($importId);

    $avgProcessorProcessingRecordCount = AvgProcessorProcessingRecord::query()
        ->where(['import_id' => $importId])
        ->count();

    expect($avgProcessorProcessingRecordCount)
        ->toBe(0);

    /** @var AvgProcessorProcessingRecordFactory $avgProcessorProcessingRecordFactory */
    $avgProcessorProcessingRecordFactory = $this->app->get(AvgProcessorProcessingRecordFactory::class);
    $avgResponsibleProcessingRecord = $avgProcessorProcessingRecordFactory->create($data, $this->organisation->id);

    $avgProcessorProcessingRecordCount = AvgProcessorProcessingRecord::query()
        ->where(['import_id' => $importId])
        ->count();

    expect($avgProcessorProcessingRecordCount)
        ->toBe(1);

    /** @var TestResponse $response */
    $response = $this->get(route('filament.admin.resources.avg-processor-processing-records.edit', [
        'tenant' => $this->organisation,
        'record' => $avgResponsibleProcessingRecord->id,
    ]));
    $response->assertOk();
});

it('skips the import when model with import_id exists', function (): void {
    $importId = fake()->importId();
    $name = fake()->word();

    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::factory()
        ->create([
            'import_id' => $importId,
            'name' => $name,
        ]);

    /** @var AvgProcessorProcessingRecordFactory $avgProcessorProcessingRecordFactory */
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
    $organisation = Organisation::factory()->create();
    $importId = fake()->slug();
    $status = fake()->word();

    ConfigHelper::set('import.states_to_skip_import', [$status]);

    $data = getAvgProcessorProcessingRecordFactoryImportData($importId);
    $data['Status'] = $status;

    /** @var AvgProcessorProcessingRecordFactory $avgProcessorProcessingRecordFactory */
    $avgProcessorProcessingRecordFactory = $this->app->get(AvgProcessorProcessingRecordFactory::class);
    $avgProcessorProcessingRecordFactory->create($data, $organisation->id);

    $this->assertDatabaseMissing(AvgProcessorProcessingRecord::class, [
        'import_id' => $importId,
    ]);
});

it('imports the measures & description correctly', function (): void {
    $organisation = Organisation::factory()->create();
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

    /** @var AvgProcessorProcessingRecordFactory $avgProcessorProcessingRecordFactory */
    $avgProcessorProcessingRecordFactory = $this->app->get(AvgProcessorProcessingRecordFactory::class);
    $avgProcessorProcessingRecordFactory->create($data, $organisation->id);

    /** @var AvgProcessorProcessingRecord $avgProcessorProcessingRecord */
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
    $organisation = Organisation::factory()->create();
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

    /** @var AvgProcessorProcessingRecordFactory $avgProcessorProcessingRecordFactory */
    $avgProcessorProcessingRecordFactory = $this->app->get(AvgProcessorProcessingRecordFactory::class);
    $avgProcessorProcessingRecordFactory->create($data, $organisation->id);

    /** @var AvgProcessorProcessingRecord $avgProcessorProcessingRecord */
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
    $version = fake()->randomDigit();

    ConfigHelper::set(sprintf('import.value_converters.snapshot_state.%s', $status), Approved::class);

    $organisation = Organisation::factory()->create();
    $importId = fake()->slug();
    $data = getAvgProcessorProcessingRecordFactoryImportData($importId);
    $data['Status'] = $status;
    $data['Versie'] = $version;

    /** @var AvgProcessorProcessingRecordFactory $avgProcessorProcessingRecordFactory */
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
