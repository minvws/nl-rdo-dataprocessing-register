@php
    /** @formatter:off */
    /** @var App\Models\Avg\AvgProcessorProcessingRecord $record */
@endphp

# {{ $record->name }}

@if($record->avgGoals->count() === 1)
Hier vindt u een beschrijving van gegevensverwerking _{{ trim($record->name) }}_ in het kader van de Algemene Verordening Gegevensbescherming (AVG), met als doel:

{{ $record->avgGoals->first()->goal }}
@else
Hier vindt u een beschrijving van gegevensverwerking _{{ trim($record->name) }}_ in het kader van de Algemene Verordening Gegevensbescherming (AVG), met als doelen:

@foreach ($record->avgGoals as $goal)
- {{ $goal->goal }}
@endforeach
@endif

# Automatische besluitvorming
Er is <u>{{ $record->decision_making ? 'wel' : 'geen'}}</u> sprake van besluitvorming over persoonsgegevens op basis van automatisch verwerkte gegevens.

@if($record->receivers->count() > 0)
# Ontvangers
Gegevens worden verstrekt aan onderstaande 'ontvangers'.

@foreach ($record->receivers as $receiver)
- {{ $receiver->description }}
@endforeach
@endif

# Doorgifte buiten EER
Er is <u>{{ $record->outside_eu ? 'wel' : 'geen'}}</u> sprake van doorgifte van persoonsgegevens aan één of meer landen buiten de Europese Economische Ruimte (EER) of aan een internationale organisatie.

<!--- #App\Models\Responsible# --->
