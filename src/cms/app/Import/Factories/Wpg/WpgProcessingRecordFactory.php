<?php

declare(strict_types=1);

namespace App\Import\Factories\Wpg;

use App\Components\Uuid\Uuid;
use App\Import\Factories\AbstractFactory;
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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\ModelStates\Exceptions\InvalidConfig;
use Throwable;

use function in_array;
use function sprintf;

class WpgProcessingRecordFactory extends AbstractFactory implements Factory
{
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
     * @throws InvalidConfig
     * @throws Throwable
     */
    public function create(array $data, string $organisationId): ?Model
    {
        $wpgProcessingRecord = WpgProcessingRecord::firstOrNew([
            'import_id' => $data['Id'],
            'organisation_id' => $organisationId,
        ]);

        if ($wpgProcessingRecord->exists) {
            return null;
        }

        if ($this->skipState($data['Status'])) {
            return null;
        }

        $wpgProcessingRecord->id = Uuid::generate()->toString();
        $wpgProcessingRecord->organisation_id = $organisationId;
        $wpgProcessingRecord->import_number = $data['Nummer'];
        $wpgProcessingRecord->import_id = $this->toString($data['Id']);
        $wpgProcessingRecord->created_at = $this->toCarbon($data['AanmaakDatum']);
        $wpgProcessingRecord->updated_at = $this->toCarbon($data['LaatsteWijzigDatum']);

        $wpgProcessingRecord->name = $this->toString($data['Naam']);

        $wpgProcessingRecord->suspects = $this->toBoolean($data['Betrokkenen']['Verdachten']);
        $wpgProcessingRecord->victims = $this->toBoolean($data['Betrokkenen']['Slachtoffers']);
        $wpgProcessingRecord->convicts = $this->toBoolean($data['Betrokkenen']['Veroordeelden']);
        $wpgProcessingRecord->third_parties = $this->toBoolean($data['Betrokkenen']['Derden']);
        $wpgProcessingRecord->third_party_explanation = $this->toString($data['Betrokkenen']['ToelichtingDerden']);

        $wpgProcessingRecord->article_15 = $this->toBoolean($data['Ontvanger']['Artikel15']);
        $wpgProcessingRecord->article_15_a = $this->toBoolean($data['Ontvanger']['Artikel15A']);
        $wpgProcessingRecord->article_16 = $this->toBoolean($data['Ontvanger']['Artikel16']);
        $wpgProcessingRecord->article_17 = $this->toBoolean($data['Ontvanger']['Artikel17']);
        $wpgProcessingRecord->article_17_a = $this->toBoolean($data['Ontvanger']['Artikel17A']);
        $wpgProcessingRecord->article_18 = $this->toBoolean($data['Ontvanger']['Artikel18']);
        $wpgProcessingRecord->article_19 = $this->toBoolean($data['Ontvanger']['Artikel19']);
        $wpgProcessingRecord->article_20 = $this->toBoolean($data['Ontvanger']['Artikel20']);
        $wpgProcessingRecord->article_22 = $this->toBoolean($data['Ontvanger']['Artikel22']);
        $wpgProcessingRecord->explanation_available = $this->toString($data['Ontvanger']['ToelichtingTerBeschikking']);
        $wpgProcessingRecord->explanation_provisioning = $this->toString($data['Ontvanger']['ToelichtingVertrekking']);
        $wpgProcessingRecord->explanation_transfer = $this->toString($data['Ontvanger']['ToelichtingDoorgifte']);

        $wpgProcessingRecord->has_security = $this->toBoolean($data['Beveiliging']['HasBeveiliging']);
        $maatregelen = $data['Beveiliging']['Maatregelen'];
        $wpgProcessingRecord->measures_implemented = in_array(
            'Vastgesteld beveiligingsbeleid dat ook is geïmplementeerd',
            $this->toArray($maatregelen),
            true,
        );
        $wpgProcessingRecord->measures_description = $this->toString([
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
                    $wpgProcessingRecord->other_measures = true;
                    break;
                }
            }
        }
        $wpgProcessingRecord->has_pseudonymization = $this->toBoolean($data['Beveiliging']['Pseudonimisering']);
        $wpgProcessingRecord->pseudonymization = $this->toString($data['Beveiliging']['Pseudonimisering']);

        $wpgProcessingRecord->decision_making = $this->toBoolean($data['Besluitvorming']['HasBesluitvorming']);
        $wpgProcessingRecord->logic = $this->toString($data['Besluitvorming']['Logica']);
        $wpgProcessingRecord->consequences = $this->toString($data['Besluitvorming']['BelangGevolgen']);

        $wpgProcessingRecord->geb_pia = $this->toBoolean($data['GebPia']['IsHoogRisicoRechtenVrijheden']);

        $wpgProcessingRecord->wpg_processing_record_service_id = $this->lookupListFactory->create(
            WpgProcessingRecordService::class,
            $organisationId,
            $data['Dienst'],
        )?->id;

        $wpgProcessingRecord->save();

        $this->createRelations(
            $wpgProcessingRecord,
            $organisationId,
            'contactPersons',
            $data['Contactpersonen'],
            $this->contactPersonFactory,
        );
        $this->createRelations($wpgProcessingRecord, $organisationId, 'processors', $data['Verwerkers'], $this->processorFactory);
        $this->createRelations($wpgProcessingRecord, $organisationId, 'remarks', $data['Opmerkingen'], $this->remarkFactory);
        $this->createRelations(
            $wpgProcessingRecord,
            $organisationId,
            'responsibles',
            $data['Verantwoordelijken'],
            $this->responsibleFactory,
        );
        $this->createRelations($wpgProcessingRecord, $organisationId, 'systems', $data['Systemen'], $this->systemFactory);
        $this->createRelations($wpgProcessingRecord, $organisationId, 'wpgGoals', $data['Doelen'], $this->wpgGoalFactory);

        $this->createSnapshot($wpgProcessingRecord, $data['Versie'], $data['Status'], $this->snapshotFactory);

        return $wpgProcessingRecord;
    }
}
