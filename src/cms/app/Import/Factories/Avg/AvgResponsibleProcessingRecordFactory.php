<?php

declare(strict_types=1);

namespace App\Import\Factories\Avg;

use App\Components\Uuid\Uuid;
use App\Import\Factories\AbstractFactory;
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
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\ModelStates\Exceptions\InvalidConfig;
use Throwable;

use function count;
use function in_array;
use function sprintf;

class AvgResponsibleProcessingRecordFactory extends AbstractFactory implements Factory
{
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
     * @throws InvalidConfig
     * @throws Throwable
     */
    public function create(array $data, string $organisationId): ?Model
    {
        $organisation = Organisation::findOrFail($organisationId);
        $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::firstOrNew([
            'import_id' => $data['Id'],
            'organisation_id' => $organisationId,
        ]);

        if ($avgResponsibleProcessingRecord->exists) {
            return null;
        }

        if ($this->skipState($data['Status'])) {
            return null;
        }

        $avgResponsibleProcessingRecord->id = Uuid::generate()->toString();
        $avgResponsibleProcessingRecord->organisation_id = $organisationId;
        $avgResponsibleProcessingRecord->import_number = $data['Nummer'];
        $avgResponsibleProcessingRecord->import_id = $this->toStringOrNull($data['Id']);
        $avgResponsibleProcessingRecord->created_at = $this->toCarbon($data['AanmaakDatum']);
        $avgResponsibleProcessingRecord->updated_at = $this->toCarbon($data['LaatsteWijzigDatum']);
        $avgResponsibleProcessingRecord->review_at = CarbonImmutable::now()->addMonths($organisation->review_at_default_in_months);

        $avgResponsibleProcessingRecord->name = $this->toString($data['Naam']);
        $avgResponsibleProcessingRecord->responsibility_distribution = $this->toString($data['VerdelingVerantwoordelijkheid']);

        $avgResponsibleProcessingRecord->has_security = $this->toBoolean($data['Beveiliging']['HasBeveiliging']);
        $maatregelen = $data['Beveiliging']['Maatregelen'];
        $avgResponsibleProcessingRecord->measures_implemented = in_array(
            'Vastgesteld beveiligingsbeleid dat ook is geïmplementeerd',
            $this->toArray($maatregelen),
            true,
        );
        $avgResponsibleProcessingRecord->measures_description = $this->toString([
            sprintf('Encryptie: %s', $this->toString($data['Beveiliging']['Encryptie'])),
            sprintf('ElectronischeWeg: %s', $this->toString($data['Beveiliging']['ElectronischeWeg'])),
            sprintf('Toegang: %s', $this->toString($data['Beveiliging']['Toegang'])),
            sprintf('Verwerkers: %s', $this->toString($data['Beveiliging']['Verwerkers'])),
            sprintf('Verantwoordelijken: %s', $this->toString($data['Beveiliging']['Verantwoordelijken'])),
            sprintf('Maatregelen: %s', $this->toString($maatregelen)),
        ]);
        if ($maatregelen !== null) {
            foreach ($maatregelen as $maatregel) {
                if (Str::of($maatregel)->startsWith('Overige beveiligingsmaatregelen')) {
                    $avgResponsibleProcessingRecord->other_measures = true;
                    break;
                }
            }
        }
        $avgResponsibleProcessingRecord->has_pseudonymization = $this->toBoolean($data['Beveiliging']['Pseudonimisering'] !== 'Nee');
        $avgResponsibleProcessingRecord->pseudonymization = $this->toString($data['Beveiliging']['Pseudonimisering']);

        $avgResponsibleProcessingRecord->decision_making = $this->toBoolean($data['Besluitvorming']['HasBesluitvorming']);
        $avgResponsibleProcessingRecord->logic = $this->toStringOrNull($data['Besluitvorming']['Logica']);
        $avgResponsibleProcessingRecord->importance_consequences = $this->toStringOrNull($data['Besluitvorming']['BelangGevolgen']);

        $avgResponsibleProcessingRecord->outside_eu = $this->toBoolean($data['Doorgifte']['BuitenEu']);
        $avgResponsibleProcessingRecord->outside_eu_description = $this->toString($data['Doorgifte']['BuitenEuOmschrijving']);
        $avgResponsibleProcessingRecord->outside_eu_protection_level = $this->toBoolean(
            $data['Doorgifte']['BuitenEuPassendBeschermingsniveau'],
        );
        $avgResponsibleProcessingRecord->outside_eu_protection_level_description = $this->toStringOrNull(
            $data['Doorgifte']['BuitenEuPassendBeschermingsniveauOmschrijving'],
        );

        $avgResponsibleProcessingRecord->geb_dpia_executed = $this->toBoolean($data['GebPia']['IsGebpiaAlUitgevoerd']);
        $avgResponsibleProcessingRecord->geb_dpia_automated = $this->toBoolean($data['GebPia']['IsGeautomatiseerdProfilering']);
        $avgResponsibleProcessingRecord->geb_dpia_large_scale_processing = $this->toBoolean($data['GebPia']['IsGrootschaligeVerwerking']);
        $avgResponsibleProcessingRecord->geb_dpia_large_scale_monitoring = $this->toBoolean($data['GebPia']['IsGrootschaligeMonitoring']);
        $avgResponsibleProcessingRecord->geb_dpia_list_required = $this->toBoolean($data['GebPia']['StaatOpLijstVerplichteGebpia']);
        $avgResponsibleProcessingRecord->geb_dpia_criteria_wp248 = $this->toBoolean($data['GebPia']['VoeldoetAanTweeCriteriaWP248']);
        $avgResponsibleProcessingRecord->geb_dpia_high_risk_freedoms = $this->toBoolean($data['GebPia']['IsHoogRisicoRechtenVrijheden']);

        $avgResponsibleProcessingRecord->has_processors = count($data['Verwerkers']) > 0;
        $avgResponsibleProcessingRecord->has_systems = count($data['Systemen']) > 0;

        $avgResponsibleProcessingRecord->avg_responsible_processing_record_service_id = $this->lookupListFactory->create(
            AvgResponsibleProcessingRecordService::class,
            $organisationId,
            $data['Dienst'],
        )?->id;

        $avgResponsibleProcessingRecord->save();

        $this->createRelations($avgResponsibleProcessingRecord, $organisationId, 'avgGoals', $data['Doelen'], $this->avgGoalFactory);
        $this->createRelations(
            $avgResponsibleProcessingRecord,
            $organisationId,
            'contactPersons',
            $data['Contactpersonen'],
            $this->contactPersonFactory,
        );
        $this->createRelations(
            $avgResponsibleProcessingRecord,
            $organisationId,
            'processors',
            $data['Verwerkers'],
            $this->processorFactory,
        );
        $this->createRelations($avgResponsibleProcessingRecord, $organisationId, 'receivers', $data['Ontvangers'], $this->receiverFactory);
        $this->createRelations($avgResponsibleProcessingRecord, $organisationId, 'remarks', $data['Opmerkingen'], $this->remarkFactory);
        $this->createRelations(
            $avgResponsibleProcessingRecord,
            $organisationId,
            'responsibles',
            $data['Verantwoordelijken'],
            $this->responsibleFactory,
        );
        $this->createRelations($avgResponsibleProcessingRecord, $organisationId, 'systems', $data['Systemen'], $this->systemFactory);
        $this->createRelations(
            $avgResponsibleProcessingRecord,
            $organisationId,
            'stakeholders',
            $data['Betrokkenen'],
            $this->stakeholderFactory,
        );

        $this->createSnapshot($avgResponsibleProcessingRecord, $data['Versie'], $data['Status'], $this->snapshotFactory);

        return $avgResponsibleProcessingRecord;
    }
}
