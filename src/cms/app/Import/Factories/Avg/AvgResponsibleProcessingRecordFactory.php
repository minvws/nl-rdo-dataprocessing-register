<?php

declare(strict_types=1);

namespace App\Import\Factories\Avg;

use App\Components\Uuid\UuidInterface;
use App\Config\Config;
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
use App\Import\Factories\StakeholderFactory;
use App\Import\Factories\SystemFactory;
use App\Import\Factory;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecordService;
use App\Models\Organisation;
use App\Services\Snapshot\SnapshotFactory;
use App\ValueObjects\CalendarDate;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use Spatie\ModelStates\Exceptions\InvalidConfig;
use Throwable;
use Webmozart\Assert\Assert;

use function count;
use function in_array;
use function sprintf;

/**
 * @implements Factory<AvgResponsibleProcessingRecord>
 */
class AvgResponsibleProcessingRecordFactory implements Factory
{
    use DataConverters;
    use RelationHelper;
    use SnapshotHelper;
    use StateHelper;

    public function __construct(
        private readonly AvgGoalFactory $avgGoalFactory,
        private readonly ContactPersonFactory $contactPersonFactory,
        private readonly LookupListFactory $lookupListFactory,
        private readonly ProcessorFactory $processorFactory,
        private readonly ReceiverFactory $receiverFactory,
        private readonly RemarkFactory $remarkFactory,
        private readonly ResponsibleFactory $responsibleFactory,
        private readonly SnapshotFactory $snapshotFactory,
        private readonly SystemFactory $systemFactory,
        private readonly StakeholderFactory $stakeholderFactory,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     *
     * @throws InvalidConfig
     * @throws Throwable
     */
    public function create(array $data, UuidInterface $organisationId): ?AvgResponsibleProcessingRecord
    {
        /** @var AvgResponsibleProcessingRecord $avgResponsibleProcessingRecord */
        $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::firstOrNew([
            'import_id' => $data['Id'],
            'organisation_id' => $organisationId,
        ]);

        if ($avgResponsibleProcessingRecord->exists) {
            return null;
        }

        if ($this->skipState($this->toString($data, 'Status'))) {
            return null;
        }

        /** @var Organisation $organisation */
        $organisation = Organisation::findOrFail($organisationId);

        $avgResponsibleProcessingRecord->organisation_id = $organisationId;
        $avgResponsibleProcessingRecord->import_number = $this->toStringOrNull($data, 'Nummer');
        $avgResponsibleProcessingRecord->import_id = $this->toStringOrNull($data, 'Id');
        $avgResponsibleProcessingRecord->created_at = $this->toCarbon($data, 'AanmaakDatum');
        $avgResponsibleProcessingRecord->updated_at = $this->toCarbon($data, 'LaatsteWijzigDatum');
        $avgResponsibleProcessingRecord->review_at = CalendarDate::instance(CarbonImmutable::now(Config::string('app.display_timezone')))
            ->addMonths($organisation->review_at_default_in_months);

        $avgResponsibleProcessingRecord->name = $this->toString($data, 'Naam');
        $avgResponsibleProcessingRecord->responsibility_distribution = $this->toString($data, 'VerdelingVerantwoordelijkheid');

        $avgResponsibleProcessingRecord->has_security = $this->toBoolean($data, 'Beveiliging.HasBeveiliging');
        $maatregelen = $this->toArray($data, 'Beveiliging.Maatregelen');
        Assert::allString($maatregelen);
        $avgResponsibleProcessingRecord->measures_implemented = in_array(
            'Vastgesteld beveiligingsbeleid dat ook is geïmplementeerd',
            $maatregelen,
            true,
        );
        $avgResponsibleProcessingRecord->measures_description = $this->toImplodedString([
            sprintf('Encryptie: %s', $this->toString($data, 'Beveiliging.Encryptie')),
            sprintf('ElectronischeWeg: %s', $this->toString($data, 'Beveiliging.ElectronischeWeg')),
            sprintf('Toegang: %s', $this->toString($data, 'Beveiliging.Toegang')),
            sprintf('Verwerkers: %s', $this->toString($data, 'Beveiliging.Verwerkers')),
            sprintf('Verantwoordelijken: %s', $this->toString($data, 'Beveiliging.Verantwoordelijken')),
            sprintf('Maatregelen: %s', $this->toImplodedString($maatregelen)),
        ]);
        foreach ($maatregelen as $maatregel) {
            if (Str::of($maatregel)->startsWith('Overige beveiligingsmaatregelen')) {
                $avgResponsibleProcessingRecord->other_measures = true;
                break;
            }
        }
        $avgResponsibleProcessingRecord->has_pseudonymization = $this->toString($data, 'Beveiliging.Pseudonimisering') !== 'Nee';
        $avgResponsibleProcessingRecord->pseudonymization = $this->toString($data, 'Beveiliging.Pseudonimisering');

        $avgResponsibleProcessingRecord->decision_making = $this->toBoolean($data, 'Besluitvorming.HasBesluitvorming');
        $avgResponsibleProcessingRecord->logic = $this->toStringOrNull($data, 'Besluitvorming.Logica');
        $avgResponsibleProcessingRecord->importance_consequences = $this->toStringOrNull($data, 'Besluitvorming.BelangGevolgen');

        $avgResponsibleProcessingRecord->outside_eu = $this->toBoolean($data, 'Doorgifte.BuitenEu');
        $avgResponsibleProcessingRecord->outside_eu_description = $this->toString($data, 'Doorgifte.BuitenEuOmschrijving');
        $avgResponsibleProcessingRecord->outside_eu_protection_level = $this->toBoolean(
            $data,
            'Doorgifte.BuitenEuPassendBeschermingsniveau',
        );
        $avgResponsibleProcessingRecord->outside_eu_protection_level_description = $this->toStringOrNull(
            $data,
            'Doorgifte.BuitenEuPassendBeschermingsniveauOmschrijving',
        );

        $avgResponsibleProcessingRecord->geb_dpia_executed = $this->toBoolean($data, 'GebPia,IsGebpiaAlUitgevoerd');
        $avgResponsibleProcessingRecord->geb_dpia_automated = $this->toBoolean($data, 'GebPia,IsGeautomatiseerdProfilering');
        $avgResponsibleProcessingRecord->geb_dpia_large_scale_processing = $this->toBoolean($data, 'GebPia,IsGrootschaligeVerwerking');
        $avgResponsibleProcessingRecord->geb_dpia_large_scale_monitoring = $this->toBoolean($data, 'GebPia,IsGrootschaligeMonitoring');
        $avgResponsibleProcessingRecord->geb_dpia_list_required = $this->toBoolean($data, 'GebPia,StaatOpLijstVerplichteGebpia');
        $avgResponsibleProcessingRecord->geb_dpia_criteria_wp248 = $this->toBoolean($data, 'GebPia,VoeldoetAanTweeCriteriaWP248');
        $avgResponsibleProcessingRecord->geb_dpia_high_risk_freedoms = $this->toBoolean($data, 'GebPia,IsHoogRisicoRechtenVrijheden');

        $avgResponsibleProcessingRecord->has_processors = count($this->toArray($data, 'Verwerkers')) > 0;
        $avgResponsibleProcessingRecord->has_systems = count($this->toArray($data, 'Systemen')) > 0;

        $avgResponsibleProcessingRecord->avg_responsible_processing_record_service_id = $this->lookupListFactory->create(
            AvgResponsibleProcessingRecordService::class,
            $organisationId,
            $this->toStringOrNull($data, 'Dienst'),
        )?->id;

        $avgResponsibleProcessingRecord->save();

        $this->createRelations($avgResponsibleProcessingRecord->avgGoals(), $organisationId, $data, 'Doelen', $this->avgGoalFactory);
        $this->createRelations(
            $avgResponsibleProcessingRecord->contactPersons(),
            $organisationId,
            $data,
            'Contactpersonen',
            $this->contactPersonFactory,
        );
        $this->createRelations(
            $avgResponsibleProcessingRecord->processors(),
            $organisationId,
            $data,
            'Verwerkers',
            $this->processorFactory,
        );
        $this->createRelations($avgResponsibleProcessingRecord->receivers(), $organisationId, $data, 'Ontvangers', $this->receiverFactory);
        $this->createRelations($avgResponsibleProcessingRecord->remarks(), $organisationId, $data, 'Opmerkingen', $this->remarkFactory);
        $this->createRelations(
            $avgResponsibleProcessingRecord->responsibles(),
            $organisationId,
            $data,
            'Verantwoordelijken',
            $this->responsibleFactory,
        );
        $this->createRelations($avgResponsibleProcessingRecord->systems(), $organisationId, $data, 'Systemen', $this->systemFactory);
        $this->createRelations(
            $avgResponsibleProcessingRecord->stakeholders(),
            $organisationId,
            $data,
            'Betrokkenen',
            $this->stakeholderFactory,
        );

        $this->createSnapshot(
            $avgResponsibleProcessingRecord,
            $this->toInt($data, 'Versie'),
            $this->toString($data, 'Status'),
            $this->snapshotFactory,
        );

        return $avgResponsibleProcessingRecord;
    }
}
