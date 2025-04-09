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
use App\Import\Factories\SystemFactory;
use App\Import\Factory;
use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Avg\AvgProcessorProcessingRecordService;
use App\Services\Snapshot\SnapshotFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\ModelStates\Exceptions\InvalidConfig;
use Throwable;

use function in_array;
use function sprintf;

class AvgProcessorProcessingRecordFactory extends AbstractFactory implements Factory
{
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
     * @throws InvalidConfig
     * @throws Throwable
     */
    public function create(array $data, string $organisationId): ?Model
    {
        $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::firstOrNew([
            'import_id' => $data['Id'],
            'organisation_id' => $organisationId,
        ]);

        if ($avgProcessorProcessingRecord->exists) {
            return null;
        }

        if ($this->skipState($data['Status'])) {
            return null;
        }

        $avgProcessorProcessingRecord->id = Uuid::generate()->toString();
        $avgProcessorProcessingRecord->organisation_id = $organisationId;
        $avgProcessorProcessingRecord->import_number = $data['Nummer'];
        $avgProcessorProcessingRecord->import_id = $this->toStringOrNull($data['Id']);
        $avgProcessorProcessingRecord->created_at = $this->toCarbon($data['AanmaakDatum']);
        $avgProcessorProcessingRecord->updated_at = $this->toCarbon($data['LaatsteWijzigDatum']);

        $avgProcessorProcessingRecord->name = $this->toString($data['Naam']);
        $avgProcessorProcessingRecord->responsibility_distribution = $this->toString($data['VerdelingVerantwoordelijkheid']);

        $avgProcessorProcessingRecord->has_security = $this->toBoolean($data['Beveiliging']['HasBeveiliging']);
        $maatregelen = $data['Beveiliging']['Maatregelen'];
        $avgProcessorProcessingRecord->measures_implemented = in_array(
            'Vastgesteld beveiligingsbeleid dat ook is geïmplementeerd',
            $this->toArray($maatregelen),
            true,
        );
        $avgProcessorProcessingRecord->measures_description = $this->toString([
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
                    $avgProcessorProcessingRecord->other_measures = true;
                    break;
                }
            }
        }
        $avgProcessorProcessingRecord->has_pseudonymization = $this->toBoolean($data['Beveiliging']['Pseudonimisering'] !== 'Nee');
        $avgProcessorProcessingRecord->pseudonymization = $this->toString($data['Beveiliging']['Pseudonimisering']);

        $avgProcessorProcessingRecord->outside_eu = $this->toBoolean($data['Doorgifte']['BuitenEu']);
        $avgProcessorProcessingRecord->country = $this->toString($data['Doorgifte']['BuitenEuOmschrijving']);
        $avgProcessorProcessingRecord->outside_eu_protection_level = $this->toBoolean(
            $data['Doorgifte']['BuitenEuPassendBeschermingsniveau'],
        );
        $avgProcessorProcessingRecord->outside_eu_protection_level_description = $this->toString(
            $data['Doorgifte']['BuitenEuPassendBeschermingsniveauOmschrijving'],
        );

        $avgProcessorProcessingRecord->decision_making = $this->toBoolean($data['Besluitvorming']['HasBesluitvorming']);
        $avgProcessorProcessingRecord->logic = $this->toString($data['Besluitvorming']['Logica']);
        $avgProcessorProcessingRecord->importance_consequences = $this->toString($data['Besluitvorming']['BelangGevolgen']);

        $avgProcessorProcessingRecord->geb_pia = $this->toBoolean($data['GebPia']['Uitgevoerd']);

        $avgProcessorProcessingRecord->avg_processor_processing_record_service_id = $this->lookupListFactory->create(
            AvgProcessorProcessingRecordService::class,
            $organisationId,
            $data['Dienst'],
        )?->id;

        $avgProcessorProcessingRecord->save();

        $this->createRelations($avgProcessorProcessingRecord, $organisationId, 'avgGoals', $data['Doelen'], $this->avgGoalFactory);
        $this->createRelations($avgProcessorProcessingRecord, $organisationId, 'processors', $data['Verwerkers'], $this->processorFactory);
        $this->createRelations(
            $avgProcessorProcessingRecord,
            $organisationId,
            'contactPersons',
            $data['Contactpersonen'],
            $this->contactPersonFactory,
        );
        $this->createRelations($avgProcessorProcessingRecord, $organisationId, 'receivers', $data['Ontvangers'], $this->receiverFactory);
        $this->createRelations($avgProcessorProcessingRecord, $organisationId, 'remarks', $data['Opmerkingen'], $this->remarkFactory);
        $this->createRelations(
            $avgProcessorProcessingRecord,
            $organisationId,
            'responsibles',
            $data['Verantwoordelijken'],
            $this->responsibleFactory,
        );
        $this->createRelations($avgProcessorProcessingRecord, $organisationId, 'systems', $data['Systemen'], $this->systemFactory);

        $this->createSnapshot($avgProcessorProcessingRecord, $data['Versie'], $data['Status'], $this->snapshotFactory);

        return $avgProcessorProcessingRecord;
    }
}
