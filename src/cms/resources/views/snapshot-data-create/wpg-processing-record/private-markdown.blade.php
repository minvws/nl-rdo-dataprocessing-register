@php
    /** @var App\Models\Wpg\WpgProcessingRecord $record */
@endphp

# {{ $record->name }}

@foreach ($record->wpgGoals as $goal)
- {{ $goal->description }}
@endforeach
