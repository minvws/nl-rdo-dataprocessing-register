@php
    $states = $getState();

    if ($states instanceof \Illuminate\Support\Collection) {
        $states = $states->all();
    }

    $textAlternatives = array_filter(array_map($getTextAlternative, \Illuminate\Support\Arr::wrap($states)));
@endphp

@if (count($textAlternatives) > 0)
    <span class="sr-only">{{ implode(', ', $textAlternatives) }}</span>
@endif

@include('filament-tables::columns.icon-column')
