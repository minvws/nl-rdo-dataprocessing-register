@php
    /** @var App\Models\Responsible $record */
@endphp
- **{{ __('responsible.name') }}**: {{ $record->name }}
@isset($record->address)
@isset($record->address->address)
  - **{{ __('address.section_address') }}**: {{ $record->address->address }}<br>{{ $record->address->postal_code }} {{ $record->address->city }}<br>{{ $record->address->country }}
@endisset
@isset($record->address->postbox)
  - **{{ __('address.section_postbox') }}**: {{ $record->address->postbox }}<br>{{ $record->address->postbox_postal_code }} {{ $record->address->postbox_city }}<br>{{ $record->address->postbox_country }}
@endisset
@endisset
