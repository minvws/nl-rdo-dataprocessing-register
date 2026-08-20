@php
    /** @var App\Models\Wpg\WpgProcessingRecord $record */
@endphp

## {{ __('wpg_processing_record.step_processing_name') }}

- **{{ __('processing_record.number') }}**: {!! Str::toSingleLineEscapedString($record->getNumber()) !!}
- **{{ __('processing_record.import_number') }}**: {!! Str::toSingleLineEscapedString($record->import_id) !!}
- **{{ __('processing_record.name') }}**: {!! Str::toSingleLineEscapedString($record->name) !!}
- **{{ __('general.data_collection_source') }}**: {{ __(sprintf('core_entity_level.%s', $record->data_collection_source->value)) }}
- **{{ __('wpg_processing_record_service.model_singular') }}**: {!! Str::toSingleLineEscapedString($record->wpgProcessingRecordService?->name) !!}
- **{{ __('tag.model_plural') }}**: {!! $record->tags->isEmpty() ? '-' : Str::toSingleLineEscapedString($record->tags->implode(', ')) !!}
- **{{ __('general.review_at') }}**: {{ DateFormat::toDate($record->review_at) }}
- **{{ __('general.parent') }}**: {!! Str::toSingleLineEscapedString($record->parent?->getNumber(), '-') !!}

## {{ __('wpg_processing_record.step_responsible') }}

<!--- #App\Models\Responsible# --->

## {{ __('wpg_processing_record.step_processor') }}

- **{{ __('wpg_processing_record.has_processors') }}**: {{ $record->has_processors ? 'ja' : 'nee' }}

<!--- #App\Models\Processor# --->

## {{ __('wpg_processing_record.step_receiver') }}

- **{{ __('wpg_processing_record.help_receiver_provisioning') }}**
  - **{{ __('wpg_processing_record.article_15') }}**: {{ $record->article_15 ? 'ja' : 'nee' }}
  - **{{ __('wpg_processing_record.article_15_a') }}**: {{ $record->article_15_a ? 'ja' : 'nee' }}
  - **{{ __('wpg_processing_record.explanation_available') }}**: {!! Str::toSingleLineEscapedString($record->explanation_available, '-') !!}
- **{{ __('wpg_processing_record.help_receiver_third_party') }}**
  - **{{ __('wpg_processing_record.article_16') }}**: {{ $record->article_16 ? 'ja' : 'nee' }}
  - **{{ __('wpg_processing_record.article_17') }}**: {{ $record->article_17 ? 'ja' : 'nee' }}
  - **{{ __('wpg_processing_record.article_18') }}**: {{ $record->article_18 ? 'ja' : 'nee' }}
  - **{{ __('wpg_processing_record.article_19') }}**: {{ $record->article_19 ? 'ja' : 'nee' }}
  - **{{ __('wpg_processing_record.article_20') }}**: {{ $record->article_20 ? 'ja' : 'nee' }}
  - **{{ __('wpg_processing_record.article_22') }}**: {{ $record->article_22 ? 'ja' : 'nee' }}
  - **{{ __('wpg_processing_record.article_23') }}**: {{ $record->article_23 ? 'ja' : 'nee' }}
  - **{{ __('wpg_processing_record.article_24') }}**: {{ $record->article_24 ? 'ja' : 'nee' }}
  - **{{ __('wpg_processing_record.explanation_provisioning') }}**: {!! Str::toSingleLineEscapedString($record->explanation_provisioning, '-') !!}
- **{{ __('wpg_processing_record.help_receiver_transfer') }}**
  - **{{ __('wpg_processing_record.article_17_a') }}**: {{ $record->article_17_a ? 'ja' : 'nee' }}
  - **{{ __('wpg_processing_record.explanation_transfer') }}**: {!! Str::toSingleLineEscapedString($record->explanation_transfer, '-') !!}

## {{ __('wpg_processing_record.step_wpg_goal') }}

@forelse ($record->wpgGoals as $goal)
- **{{ __('wpg_goal.description') }}**: {!! Str::toSingleLineEscapedString($goal->description, '-') !!}
  - **{{ __('wpg_goal.article_8') }}**: {{ $goal->article_8 ? 'ja' : 'nee' }}
  - **{{ __('wpg_goal.article_9') }}**: {{ $goal->article_9 ? 'ja' : 'nee' }}
  - **{{ __('wpg_goal.article_10_1a') }}**: {{ $goal->article_10_1a ? 'ja' : 'nee' }}
  - **{{ __('wpg_goal.article_10_1b') }}**: {{ $goal->article_10_1b ? 'ja' : 'nee' }}
  - **{{ __('wpg_goal.article_10_1c') }}**: {{ $goal->article_10_1c ? 'ja' : 'nee' }}
  - **{{ __('wpg_goal.article_12') }}**: {{ $goal->article_12 ? 'ja' : 'nee' }}
  - **{{ __('wpg_goal.article_13_1') }}**: {{ $goal->article_13_1 ? 'ja' : 'nee' }}
  - **{{ __('wpg_goal.article_13_2') }}**: {{ $goal->article_13_2 ? 'ja' : 'nee' }}
  - **{{ __('wpg_goal.article_13_3') }}**: {{ $goal->article_13_3 ? 'ja' : 'nee' }}
  - **{{ __('wpg_goal.explanation') }}**: {!! Str::toSingleLineEscapedString($goal->explanation, '-') !!}
@empty
- Geen
@endforelse

## {{ __('wpg_processing_record.step_special_police_data') }}

- **{{ __('wpg_processing_record.police_race_or_ethnicity') }}**: {{ $record->police_race_or_ethnicity ? 'ja' : 'nee' }}
- **{{ __('wpg_processing_record.police_political_attitude') }}**: {{ $record->police_political_attitude ? 'ja' : 'nee' }}
- **{{ __('wpg_processing_record.police_faith_or_belief') }}**: {{ $record->police_faith_or_belief ? 'ja' : 'nee' }}
- **{{ __('wpg_processing_record.police_association_membership') }}**: {{ $record->police_association_membership ? 'ja' : 'nee' }}
- **{{ __('wpg_processing_record.police_genetic') }}**: {{ $record->police_genetic ? 'ja' : 'nee' }}
- **{{ __('wpg_processing_record.police_identification') }}**: {{ $record->police_identification ? 'ja' : 'nee' }}
- **{{ __('wpg_processing_record.police_health') }}**: {{ $record->police_health ? 'ja' : 'nee' }}
- **{{ __('wpg_processing_record.police_sexual_life') }}**: {{ $record->police_sexual_life ? 'ja' : 'nee' }}

## {{ __('wpg_processing_record.step_decision_making') }}

- **{{ __('wpg_processing_record.decision_making') }}**: {{ $record->decision_making ? 'ja' : 'nee' }}
- **{{ __('wpg_processing_record.logic') }}**: {!! Str::toSingleLineEscapedString($record->logic, '-') !!}
- **{{ __('wpg_processing_record.consequences') }}**: {!! Str::toSingleLineEscapedString($record->consequences, '-') !!}

## {{ __('wpg_processing_record.step_system_application') }}

- **{{ __('wpg_processing_record.has_systems') }}**: {{ $record->has_systems ? 'ja' : 'nee' }}

<!--- #App\Models\System# --->

## {{ __('wpg_processing_record.step_security') }}

- **{{ __('wpg_processing_record.has_security') }}**: {{ $record->has_security ? 'ja' : 'nee' }}
- **{{ __('processor.measures') }}**
  - **{{ __('processor.measures_implemented') }}**: {{ $record->measures_implemented ? 'ja' : 'nee' }}
  - **{{ __('processor.other_measures') }}**: {{ $record->other_measures ? 'ja' : 'nee' }}
  - **{{ __('processor.measures_description') }}**: {!! Str::toSingleLineEscapedString($record->measures_description, '-') !!}
- **{{ __('wpg_processing_record.has_pseudonymization') }}**: {{ $record->has_pseudonymization ? 'ja' : 'nee' }}
  - **{{ __('wpg_processing_record.pseudonymization') }}**: {!! Str::toSingleLineEscapedString($record->pseudonymization, '-') !!}

## {{ __('wpg_processing_record.step_geb_dpia') }}

- **{{ __('wpg_processing_record.geb_pia') }}**: {{ $record->geb_pia ? 'ja' : 'nee' }}

## {{ __('wpg_processing_record.step_attachments') }}

@forelse($record->documents as $document)
- **{{ __('document.model_singular') }}**: {!! Str::toSingleLineEscapedString($document->name) !!}
@empty
- Geen
@endforelse

## {{ __('wpg_processing_record.step_remarks') }}

@forelse($record->remarks as $remark)
- **{{ __('remark.model_singular') }}**: {!! Str::toSingleLineEscapedString($remark->body, '-') !!}
@empty
- Geen
@endforelse

## {{ __('wpg_processing_record.step_categories_involved') }}

- **{{ __('wpg_processing_record.suspects') }}**: {{ $record->suspects ? 'ja' : 'nee' }}
- **{{ __('wpg_processing_record.victims') }}**: {{ $record->victims ? 'ja' : 'nee' }}
- **{{ __('wpg_processing_record.convicts') }}**: {{ $record->convicts ? 'ja' : 'nee' }}
- **{{ __('wpg_processing_record.police_justice') }}**: {{ $record->police_justice ? 'ja' : 'nee' }}
- **{{ __('wpg_processing_record.third_parties') }}**: {{ $record->third_parties ? 'ja' : 'nee' }}
- **{{ __('wpg_processing_record.third_party_explanation') }}**: {!! Str::toSingleLineEscapedString($record->third_party_explanation, '-') !!}

## {{ __('wpg_processing_record.step_publish') }}

- **{{ __('general.public_from') }}**: {{ $record->public_from ? DateFormat::toDateTime($record->public_from) : '-' }}
