@php
    /** @formatter:off */
    /** @var App\Models\Snapshot $snapshot */
@endphp

@if($snapshots->count() > 0)
<h1>Ontvangers</h1>
<p>Gegevens worden verstrekt aan onderstaande 'ontvangers'.</p>
<ul>
@foreach ($snapshots as $snapshot)
<li><span class="related-record">{!! $snapshot->snapshotData->public_markdown !!}</span></li>
@endforeach
</ul>
@endif
