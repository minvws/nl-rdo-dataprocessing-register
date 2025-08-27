<?php

declare(strict_types=1);

use App\Filament\Resources\WpgProcessingRecordResource;
use App\Import\Factories\Wpg\WpgProcessingRecordFactory;
use App\Models\Wpg\WpgProcessingRecord;
use Tests\Helpers\ConfigTestHelper;
use Tests\Helpers\Model\OrganisationTestHelper;

it('imports the model', function (): void {
    $importId = fake()->slug();

    $organisation = OrganisationTestHelper::create();
    $wpgProcessingRecordCount = WpgProcessingRecord::query()
        ->where(['import_id' => $importId])
        ->count();

    expect($wpgProcessingRecordCount)
        ->toBe(0);

    $data = getWpgProcessingRecordFactoryImportData($importId);

    $wpgProcessingRecordFactory = $this->app->get(WpgProcessingRecordFactory::class);
    $wpgProcessingRecord = $wpgProcessingRecordFactory->create($data, $organisation->id);

    $wpgProcessingRecordCount = WpgProcessingRecord::query()
        ->where(['import_id' => $importId])
        ->count();

    expect($wpgProcessingRecordCount)
        ->toBe(1);

    $this->asFilamentOrganisationUser($organisation)
        ->get(WpgProcessingRecordResource::getUrl('edit', [
            'tenant' => $organisation,
            'record' => $wpgProcessingRecord,
        ]))
        ->assertOk();
});

it('skips the import when model with import_id exists', function (): void {
    $importId = fake()->importId();
    $name = fake()->name();

    $wpgProcessingRecord = WpgProcessingRecord::factory()
        ->create([
            'import_id' => $importId,
            'name' => $name,
        ]);

    $wpgProcessingRecordFactory = $this->app->get(WpgProcessingRecordFactory::class);
    $wpgProcessingRecordFactory->create([
        'Id' => $importId, // same import_id
        'Naam' => fake()->unique()->name(), // new value for the name
    ], $wpgProcessingRecord->organisation_id);

    $wpgProcessingRecord->refresh();
    expect($wpgProcessingRecord->name)
        ->toBe($name);
});

it('skips the import when model with specified state to skip', function (): void {
    $organisation = OrganisationTestHelper::create();
    $importId = fake()->slug();
    $status = fake()->word();

    ConfigTestHelper::set('import.states_to_skip_import', [$status]);

    $data = getWpgProcessingRecordFactoryImportData($importId);
    $data['Status'] = $status;

    $wpgProcessingRecordFactory = $this->app->get(WpgProcessingRecordFactory::class);
    $wpgProcessingRecordFactory->create($data, $organisation->id);

    $this->assertDatabaseMissing(WpgProcessingRecord::class, [
        'import_id' => $importId,
    ]);
});

function getWpgProcessingRecordFactoryImportData(string $importId): array
{
    return [
        'Id' => $importId,
        'AanmaakDatum' => fake()->date('Y-m-d\TH:i:s.v'),
        'LaatsteWijzigDatum' => fake()->date('Y-m-d\TH:i:s.v'),
        'Naam' => fake()->word(),
        'Nummer' => fake()->word(),
        'Dienst' => fake()->word(),
        'Opmerkingen' => [
            ['Datum' => fake()->dateTime()->format('d-m-Y H:i'), 'Tekst' => fake()->word()],
        ],
        'Betrokkenen' => [
            'Verdachten' => fake()->boolean(),
            'Slachtoffers' => fake()->boolean(),
            'Veroordeelden' => fake()->boolean(),
            'Derden' => fake()->boolean(),
            'ToelichtingDerden' => fake()->word(),
        ],
        'Ontvanger' => [
            'GeenTerBeschikking' => fake()->boolean(),
            'GeenVerstrekking' => fake()->boolean(),
            'GeenDoorgifte' => fake()->boolean(),
            'Artikel15' => fake()->boolean(),
            'Artikel15A' => fake()->boolean(),
            'Artikel16' => fake()->boolean(),
            'Artikel17' => fake()->boolean(),
            'Artikel17A' => fake()->boolean(),
            'Artikel18' => fake()->boolean(),
            'Artikel19' => fake()->boolean(),
            'Artikel20' => fake()->boolean(),
            'Artikel22' => fake()->boolean(),
            'ToelichtingTerBeschikking' => fake()->word(),
            'ToelichtingVertrekking' => fake()->word(),
            'ToelichtingDoorgifte' => fake()->word(),
        ],
        'Beveiliging' => [
            'Pseudonimisering' => fake()->word(),
            'Encryptie' => fake()->word(),
            'ElectronischeWeg' => fake()->word(),
            'Toegang' => fake()->word(),
            'Verwerkers' => fake()->word(),
            'Verantwoordelijken' => fake()->word(),
            'Maatregelen' => [
                fake()->word(),
                sprintf('Overige beveiligingsmaatregelen: %s', fake()->sentence()),
            ],
            'HasBeveiliging' => fake()->boolean(),
        ],
        'Doorgifte' => [
            'BuitenEu' => fake()->boolean(),
            'BuitenEuOmschrijving' => fake()->word(),
            'BuitenEuPassendBeschermingsniveau' => fake()->word(),
            'BuitenEuPassendBeschermingsniveauOmschrijving' => fake()->word(),
        ],
        'Besluitvorming' => [
            'HasBesluitvorming' => fake()->boolean(),
            'Logica' => fake()->word(),
            'BelangGevolgen' => fake()->word(),
        ],
        'GebPia' => [
            'IsHoogRisicoRechtenVrijheden' => fake()->boolean(),
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
        'Doelen' => [
            [
                'Id' => $importId,
                'Omschrijving' => fake()->word(),
                'Artikel8' => fake()->boolean(),
                'Artikel9' => fake()->boolean(),
                'Artikel10Lid1A' => fake()->boolean(),
                'Artikel10Lid1B' => fake()->boolean(),
                'Artikel10Lid1C' => fake()->boolean(),
                'Artikel12' => fake()->boolean(),
                'Artikel13Lid1' => fake()->boolean(),
                'Artikel13Lid2' => fake()->boolean(),
                'Artikel13Lid3' => fake()->boolean(),
                'Toelichting' => fake()->word(),
            ],
        ],
        'Versie' => fake()->randomDigitNotNull(),
        'Status' => fake()->word(),
    ];
}
