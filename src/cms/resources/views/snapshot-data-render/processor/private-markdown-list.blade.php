@php
    /** @formatter:off */
    /** @var App\Models\Snapshot $snapshot */
@endphp

<strong>Verwerkers</strong>:
<ul class="ml-5">
@if($snapshots->count() > 0)
@foreach ($snapshots as $snapshot)
  <li><div class="related-record">{!! $snapshot->snapshotData->private_markdown !!}</div></li>
@endforeach
@else
  <li>geen</li>
@endif
</ul>
