@foreach($snapshotApprovals as $snapshotApproval)
    {{ __('snapshot.snapshot_source_type') }}: {{ __(sprintf('%s.model_singular', Str::snake(class_basename($snapshotApproval->snapshot->snapshot_source_type)))) }}<br>
    {{ __('snapshot.name') }}: {{ $snapshotApproval->snapshot->name }}<br>
    {{ __('snapshot.version') }}: {{ $snapshotApproval->snapshot->version }}<br>
@endforeach
