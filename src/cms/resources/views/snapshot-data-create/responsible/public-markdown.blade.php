@php
    /** @formatter:off */
    /** @var App\Models\Responsible $record */
@endphp

### Verantwoordelijke

{{ $record->name }}
@isset($record->address)
@isset($record->address->address)

**Bezoekadres**<br>
{{ $record->address->address }}<br>
{{ $record->address->postal_code }} {{ $record->address->city }}<br>
{{ $record->address->country }}<br>
@endisset
@isset($record->address->postbox)

**Postadres**<br>
Postbus {{ $record->address->postbox }}<br>
{{ $record->address->postbox_postal_code }} {{ $record->address->postbox_city }}<br>
{{ $record->address->postbox_country }}<br>
@endisset
@endisset
