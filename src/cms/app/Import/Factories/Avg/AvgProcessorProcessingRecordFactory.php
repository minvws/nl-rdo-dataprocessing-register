<?php

declare(strict_types=1);

namespace App\Import\Factories\Avg;

use App\Components\Uuid\UuidInterface;
use App\Import\Factories\Concerns\DataConverters;
use App\Import\Factories\Concerns\RelationHelper;
use App\Import\Factories\Concerns\SnapshotHelper;
use App\Import\Factories\Concerns\StateHelper;
use App\Import\Factories\ContactPersonFactory;
use App\Import\Factories\General\LookupListFactory;
use App\Import\Factories\ProcessorFactory;
use App\Import\Factories\ReceiverFactory;
use App\Import\Factories\RemarkFactory;
use App\Import\Factories\ResponsibleFactory;
use App\Import\Factories\SystemFactory;
use App\Import\Factory;
use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Avg\AvgProcessorProcessingRecordService;
use App\Services\Snapshot\SnapshotFactory;
use Illuminate\Support\Str;
use Spatie\ModelStates\Exceptions\InvalidConfig;
use Throwable;
use Webmozart\Assert\Assert;

use function in_array;
use function sprintf;

/**
 * @implements Factory<AvgProcessorProcessingRecord>
 */
class AvgProcessorProcessingRecordFactory implements Factory
{
    use DataConverters;
    use RelationHelper;
    use SnapshotHelper;
    use StateHelper;

    public function __construct(
        private readonly AvgGoalFactory $avgGoalFactory,
        private readonly LookupListFactory $lookupListFactory,
        private readonly ContactPersonFactory $contactPersonFactory,
        private readonly ProcessorFactory $processorFactory,
        private readonly ReceiverFactory $receiverFactory,
        private readonly ResponsibleFactory $responsibleFactory,
        private readonly SnapshotFactory $snapshotFactory,
        private readonly SystemFactory $systemFactory,
        private readonly RemarkFactory $remarkFactory,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     *
     * @throws InvalidConfig
     * @throws Throwable
     */
    public function create(array $data, UuidInterface $organisationId): ?AvgProcessorProcessingRecord
    {
        /** @var AvgProcessorProcessingRecord $avgProcessorProcessingRecord */
        $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::firstOrNew([
            'import_id' => $data['Id'],
            'organisation_id' => $organisationId,
        ]);

        if ($avgProcessorProcessingRecord->exists) {
            return null;
        }

        if ($this->skipState($this->toString($data, 'Status'))) {
            return null;
        }

        $avgProcessorProcessingRecord->organisation_id = $organisationId;
        $avgProcessorProcessingRecord->import_number = $this->toStringOrNull($data, 'Nummer');
        $avgProcessorProcessingRecord->import_id = $this->toStringOrNull($data, 'Id');
        $avgProcessorProcessingRecord->created_at = $this->toCarbon($data, 'AanmaakDatum');
        $avgProcessorProcessingRecord->updated_at = $this->toCarbon($data, 'LaatsteWijzigDatum');

        $avgProcessorProcessingRecord->name = $this->toString($data, 'Naam');
        $avgProcessorProcessingRecord->responsibility_distribution = $this->toString($data, 'VerdelingVerantwoordelijkheid');

        $avgProcessorProcessingRecord->has_security = $this->toBoolean($data, 'Beveiliging.HasBeveiliging');
        $maatregelen = $this->toArray($data, 'Beveiliging.Maatregelen');
        Assert::allString($maatregelen);
        $avgProcessorProcessingRecord->measures_implemented = in_array(
            'Vastgesteld beveiligingsbeleid dat ook is geïmplementeerd',
            $maatregelen,
            true,
        );
        $avgProcessorProcessingRecord->measures_description = $this->toImplodedString([
            sprintf('Encryptie: %s', $this->toString($data, 'Beveiliging.Encryptie')),
            sprintf('ElectronischeWeg: %s', $this->toString($data, 'Beveiliging.ElectronischeWeg')),
            sprintf('Toegang: %s', $this->toString($data, 'Beveiliging.Toegang')),
            sprintf('Verwerkers: %s', $this->toString($data, 'Beveiliging.Verwerkers')),
            sprintf('Verantwoordelijken: %s', $this->toString($data, 'Beveiliging.Verantwoordelijken')),
            sprintf('Maatregelen: %s', $this->toImplodedString($maatregelen)),
        ]);
        foreach ($maatregelen as $maatregel) {
            if (Str::of($maatregel)->startsWith('Overige beveiligingsmaatregelen')) {
                $avgProcessorProcessingRecord->other_measures = true;
                break;
            }
        }
        $avgProcessorProcessingRecord->has_pseudonymization = $this->toString($data, 'Beveiliging.Pseudonimisering') !== 'Nee';
        $avgProcessorProcessingRecord->pseudonymization = $this->toString($data, 'Beveiliging.Pseudonimisering');

        $avgProcessorProcessingRecord->outside_eu = $this->toBoolean($data, 'Doorgifte.BuitenEu');
        $avgProcessorProcessingRecord->country = $this->toString($data, 'Doorgifte.BuitenEuOmschrijving');
        $avgProcessorProcessingRecord->outside_eu_protection_level = $this->toBoolean($data, 'Doorgifte.BuitenEuPassendBeschermingsniveau');
        $avgProcessorProcessingRecord->outside_eu_protection_level_description = $this->toString(
            $data,
            'Doorgifte.BuitenEuPassendBeschermingsniveauOmschrijving',
        );

        $avgProcessorProcessingRecord->decision_making = $this->toBoolean($data, 'Besluitvorming.HasBesluitvorming');
        $avgProcessorProcessingRecord->logic = $this->toString($data, 'Besluitvorming.Logica');
        $avgProcessorProcessingRecord->importance_consequences = $this->toString($data, 'Besluitvorming.BelangGevolgen');

        $avgProcessorProcessingRecord->geb_pia = $this->toBoolean($data, 'GebPia.Uitgevoerd');

        $avgProcessorProcessingRecord->avg_processor_processing_record_service_id = $this->lookupListFactory->create(
            AvgProcessorProcessingRecordService::class,
            $organisationId,
            $this->toStringOrNull($data, 'Dienst'),
        )?->id;

        $avgProcessorProcessingRecord->save();

        $this->createRelations($avgProcessorProcessingRecord->avgGoals(), $organisationId, $data, 'Doelen', $this->avgGoalFactory);
        $this->createRelations($avgProcessorProcessingRecord->processors(), $organisationId, $data, 'Verwerkers', $this->processorFactory);
        $this->createRelations(
            $avgProcessorProcessingRecord->contactPersons(),
            $organisationId,
            $data,
            'Contactpersonen',
            $this->contactPersonFactory,
        );
        $this->createRelations($avgProcessorProcessingRecord->receivers(), $organisationId, $data, 'Ontvangers', $this->receiverFactory);
        $this->createRelations($avgProcessorProcessingRecord->remarks(), $organisationId, $data, 'Opmerkingen', $this->remarkFactory);
        $this->createRelations(
            $avgProcessorProcessingRecord->responsibles(),
            $organisationId,
            $data,
            'Verantwoordelijken',
            $this->responsibleFactory,
        );
        $this->createRelations($avgProcessorProcessingRecord->systems(), $organisationId, $data, 'Systemen', $this->systemFactory);

        $this->createSnapshot(
            $avgProcessorProcessingRecord,
            $this->toInt($data, 'Versie'),
            $this->toString($data, 'Status'),
            $this->snapshotFactory,
        );

        return $avgProcessorProcessingRecord;
    }
}
