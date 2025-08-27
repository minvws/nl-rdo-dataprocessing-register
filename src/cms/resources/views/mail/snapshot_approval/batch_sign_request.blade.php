@php
    /** @var App\Models\Snapshot $snapshot */
@endphp
<x-mail::message>
<div>
    <p>
        {{ __('snapshot_approval.mail_batch_sign_request_text') }}<br>
        <x-mail::button :url="$link">{{ __('snapshot_approval.mail_batch_sign_request_button_text') }}</x-mail::button>
    </p>
    @if($snapshotApprovalsNew->isNotEmpty())
    <p>
        <h1>{{ __('snapshot_approval.mail_batch_sign_request_heading_new') }}</h1>
        @include('mail.snapshot_approval.list', ['snapshotApprovals' => $snapshotApprovalsNew])
    </p>
    @endif
    @if($snapshotApprovalsExisting->isNotEmpty())
    <p>
        <h1>{{ __('snapshot_approval.mail_batch_sign_request_heading_existing') }}</h1>
        @include('mail.snapshot_approval.list', ['snapshotApprovals' => $snapshotApprovalsExisting])
    </p>
    @endif
</div>
</x-mail::message>
