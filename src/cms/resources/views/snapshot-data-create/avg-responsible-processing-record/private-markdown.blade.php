@php
    /** @formatter:off */
    /** @var App\Models\Avg\AvgResponsibleProcessingRecord $record */
    use App\Facades\DateFormat;
@endphp

- **Publieke review gepland**: {{ DateFormat::toDate($record->review_at) }}
- **Import id**: {{ $record->import_id }}
- **Nummer**: {{ $record->getNumber() }}
- **Aanmaakdatum**: {{ DateFormat::toDateTime($record->created_at) }}
- **Wijzigingsdatum**: {{ DateFormat::toDateTime($record->updated_at) }}
- **Verdeling verantwoordelijkheid**: {{ $record->responsibility_distribution }}
- <!--- #App\Models\ContactPerson# --->
- <!--- #App\Models\Processor# --->
- <!--- #App\Models\System# --->
- **Beveiliging**:
  - **Pseudonimisering**: {{ $record->pseudonymization }}
  - **Encryptie**: {{ $record->encryption }}
  - **Electronische weg**
  - **Toegang**
  - **Verwerkers**: {{ $record->safety_processors }}
  - **Verantwoordelijken**: {{ $record->safety_responsibles }}
  - **Maatregelen**:
- **GEB (DPIA) uitgevoerd**: {{ __(sprintf('general.%s', $record->geb_dpia_executed ? 'yes' : 'no')) }}
- **Gepubliceerd vanaf**: {{ DateFormat::toDateTime($record->public_from) }}
