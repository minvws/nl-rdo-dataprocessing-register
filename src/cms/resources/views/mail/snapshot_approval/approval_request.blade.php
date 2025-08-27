@php
    /** @var App\Models\Snapshot $snapshot */
@endphp
<x-mail::message>
<div>
    <p>
        {{ __('snapshot_approval.mail_approval_request_text') }}<br>
        <x-mail::button :url="$link">{{ __('snapshot_approval.mail_approval_request_button_text') }}</x-mail::button>
    </p>
    <p>
        {{ __('snapshot.snapshot_source_type') }}: {{ __(sprintf('%s.model_singular', Str::snake(class_basename($snapshot->snapshot_source_type)))) }}<br>
        {{ __('snapshot.snapshot_source_display_name') }}: {{ $snapshot->snapshotSource?->getDisplayName() }}<br>
        <br>
        {{ __('snapshot.name') }}: {{ $snapshot->name }}<br>
        {{ __('snapshot.version') }}: {{ $snapshot->version }}<br>
    </p>
</div>
</x-mail::message>
