@php
    /** @formatter:off */
    /** @var App\Models\Snapshot $snapshot */
@endphp
@if($snapshots->count() > 0)
<div class="fifty-fifty">
@foreach ($snapshots as $snapshot)
<div class="related-record">

{!! $snapshot->snapshotData->public_markdown !!}
</div>
@endforeach
</div>
@endif
