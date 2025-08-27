<?php

declare(strict_types=1);

namespace App\Import\Factories\Wpg;

use App\Components\Uuid\UuidInterface;
use App\Import\Factories\Concerns\DataConverters;
use App\Import\Factories\Concerns\RelationHelper;
use App\Import\Factories\Concerns\SnapshotHelper;
use App\Import\Factories\Concerns\StateHelper;
use App\Import\Factories\ContactPersonFactory;
use App\Import\Factories\General\LookupListFactory;
use App\Import\Factories\ProcessorFactory;
use App\Import\Factories\RemarkFactory;
use App\Import\Factories\ResponsibleFactory;
use App\Import\Factories\SystemFactory;
use App\Import\Factory;
use App\Models\Wpg\WpgProcessingRecord;
use App\Models\Wpg\WpgProcessingRecordService;
use App\Services\Snapshot\SnapshotFactory;
use Illuminate\Support\Str;
use Spatie\ModelStates\Exceptions\InvalidConfig;
use Throwable;
use Webmozart\Assert\Assert;

use function in_array;
use function sprintf;

/**
 * @implements Factory<WpgProcessingRecord>
 */
class WpgProcessingRecordFactory implements Factory
{
    use DataConverters;
    use RelationHelper;
    use SnapshotHelper;
    use StateHelper;

    public function __construct(
        private readonly ContactPersonFactory $contactPersonFactory,
        private readonly LookupListFactory $lookupListFactory,
        private readonly ProcessorFactory $processorFactory,
        private readonly RemarkFactory $remarkFactory,
        private readonly ResponsibleFactory $responsibleFactory,
        private readonly SnapshotFactory $snapshotFactory,
        private readonly SystemFactory $systemFactory,
        private readonly WpgGoalFactory $wpgGoalFactory,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     *
     * @throws InvalidConfig
     * @throws Throwable
     */
    public function create(array $data, UuidInterface $organisationId): ?WpgProcessingRecord
    {
        /** @var WpgProcessingRecord $wpgProcessingRecord */
        $wpgProcessingRecord = WpgProcessingRecord::firstOrNew([
            'import_id' => $data['Id'],
            'organisation_id' => $organisationId,
        ]);

        if ($wpgProcessingRecord->exists) {
            return null;
        }

        if ($this->skipState($this->toString($data, 'Status'))) {
            return null;
        }

        $wpgProcessingRecord->organisation_id = $organisationId;
        $wpgProcessingRecord->import_number = $this->toStringOrNull($data, 'Nummer');
        $wpgProcessingRecord->import_id = $this->toStringOrNull($data, 'Id');
        $wpgProcessingRecord->created_at = $this->toCarbon($data, 'AanmaakDatum');
        $wpgProcessingRecord->updated_at = $this->toCarbon($data, 'LaatsteWijzigDatum');

        $wpgProcessingRecord->name = $this->toString($data, 'Naam');

        $wpgProcessingRecord->suspects = $this->toBoolean($data, 'Betrokkenen.Verdachten');
        $wpgProcessingRecord->victims = $this->toBoolean($data, 'Betrokkenen.Slachtoffers');
        $wpgProcessingRecord->convicts = $this->toBoolean($data, 'Betrokkenen.Veroordeelden');
        $wpgProcessingRecord->third_parties = $this->toBoolean($data, 'Betrokkenen.Derden');
        $wpgProcessingRecord->third_party_explanation = $this->toString($data, 'Betrokkenen.ToelichtingDerden');

        $wpgProcessingRecord->article_15 = $this->toBoolean($data, 'Ontvanger.Artikel15');
        $wpgProcessingRecord->article_15_a = $this->toBoolean($data, 'Ontvanger.Artikel15A');
        $wpgProcessingRecord->article_16 = $this->toBoolean($data, 'Ontvanger.Artikel16');
        $wpgProcessingRecord->article_17 = $this->toBoolean($data, 'Ontvanger.Artikel17');
        $wpgProcessingRecord->article_17_a = $this->toBoolean($data, 'Ontvanger.Artikel17A');
        $wpgProcessingRecord->article_18 = $this->toBoolean($data, 'Ontvanger.Artikel18');
        $wpgProcessingRecord->article_19 = $this->toBoolean($data, 'Ontvanger.Artikel19');
        $wpgProcessingRecord->article_20 = $this->toBoolean($data, 'Ontvanger.Artikel20');
        $wpgProcessingRecord->article_22 = $this->toBoolean($data, 'Ontvanger.Artikel22');
        $wpgProcessingRecord->explanation_available = $this->toString($data, 'Ontvanger.ToelichtingTerBeschikking');
        $wpgProcessingRecord->explanation_provisioning = $this->toString($data, 'Ontvanger.ToelichtingVertrekking');
        $wpgProcessingRecord->explanation_transfer = $this->toString($data, 'Ontvanger.ToelichtingDoorgifte');

        $wpgProcessingRecord->has_security = $this->toBoolean($data, 'Beveiliging.HasBeveiliging');
        $maatregelen = $this->toArray($data, 'Beveiliging.Maatregelen');
        Assert::allString($maatregelen);
        $wpgProcessingRecord->measures_implemented = in_array(
            'Vastgesteld beveiligingsbeleid dat ook is geïmplementeerd',
            $maatregelen,
            true,
        );
        $wpgProcessingRecord->measures_description = $this->toImplodedString([
            sprintf('Encryptie: %s', $this->toString($data, 'Beveiliging.Encryptie')),
            sprintf('ElectronischeWeg: %s', $this->toString($data, 'Beveiliging.ElectronischeWeg')),
            sprintf('Toegang: %s', $this->toString($data, 'Beveiliging.Toegang')),
            sprintf('Verwerkers: %s', $this->toString($data, 'Beveiliging.Verwerkers')),
            sprintf('Verantwoordelijken: %s', $this->toString($data, 'Beveiliging.Verantwoordelijken')),
            sprintf('Maatregelen: %s', $this->toImplodedString($maatregelen)),
        ]);
        foreach ($maatregelen as $maatregel) {
            if (Str::of($maatregel)->startsWith('Overige beveiligingsmaatregelen')) {
                $wpgProcessingRecord->other_measures = true;
                break;
            }
        }
        $wpgProcessingRecord->has_pseudonymization = $this->toBoolean($data, 'Beveiliging.Pseudonimisering');
        $wpgProcessingRecord->pseudonymization = $this->toString($data, 'Beveiliging.Pseudonimisering');

        $wpgProcessingRecord->decision_making = $this->toBoolean($data, 'Besluitvorming.HasBesluitvorming');
        $wpgProcessingRecord->logic = $this->toString($data, 'Besluitvorming.Logica');
        $wpgProcessingRecord->consequences = $this->toString($data, 'Besluitvorming.BelangGevolgen');

        $wpgProcessingRecord->geb_pia = $this->toBoolean($data, 'GebPia.IsHoogRisicoRechtenVrijheden');

        $wpgProcessingRecord->wpg_processing_record_service_id = $this->lookupListFactory->create(
            WpgProcessingRecordService::class,
            $organisationId,
            $this->toStringOrNull($data, 'Dienst'),
        )?->id;

        $wpgProcessingRecord->save();

        $this->createRelations(
            $wpgProcessingRecord->contactPersons(),
            $organisationId,
            $data,
            'Contactpersonen',
            $this->contactPersonFactory,
        );
        $this->createRelations($wpgProcessingRecord->processors(), $organisationId, $data, 'Verwerkers', $this->processorFactory);
        $this->createRelations($wpgProcessingRecord->remarks(), $organisationId, $data, 'Opmerkingen', $this->remarkFactory);
        $this->createRelations(
            $wpgProcessingRecord->responsibles(),
            $organisationId,
            $data,
            'Verantwoordelijken',
            $this->responsibleFactory,
        );
        $this->createRelations($wpgProcessingRecord->systems(), $organisationId, $data, 'Systemen', $this->systemFactory);
        $this->createRelations($wpgProcessingRecord->wpgGoals(), $organisationId, $data, 'Doelen', $this->wpgGoalFactory);

        $this->createSnapshot(
            $wpgProcessingRecord,
            $this->toInt($data, 'Versie'),
            $this->toString($data, 'Status'),
            $this->snapshotFactory,
        );

        return $wpgProcessingRecord;
    }
}
