@php
    /** @var App\Models\ContactPerson $record */
@endphp

{{ $record->name }} @if($record->email)&lt;{{ $record->email }}&gt;@endif
