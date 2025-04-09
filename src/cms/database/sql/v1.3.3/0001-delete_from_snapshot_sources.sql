delete from
  "related_snapshot_sources"
where
  "snapshot_source_type" = 'App\Models\Category';

delete from
  "snapshots"
where
  "snapshot_source_type" = 'App\Models\Category';

delete from
  "related_snapshot_sources"
where
  "snapshot_source_type" = 'App\Models\Domain';

delete from
  "snapshots"
where
  "snapshot_source_type" = 'App\Models\Domain';

delete from
  "related_snapshot_sources"
where
  "snapshot_source_type" = 'App\Models\ProcessorType';

delete from
  "snapshots"
where
  "snapshot_source_type" = 'App\Models\ProcessorType';

delete from
  "related_snapshot_sources"
where
  "snapshot_source_type" = 'App\Models\ResponsibleType';

delete from
  "snapshots"
where
  "snapshot_source_type" = 'App\Models\ResponsibleType';

delete from
  "related_snapshot_sources"
where
  "snapshot_source_type" = 'App\Models\Avg\AvgGoalLegalBase';

delete from
  "snapshots"
where
  "snapshot_source_type" = 'App\Models\Avg\AvgGoalLegalBase';

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_05_31_134432_delete_from_snapshot_sources"',
    now(),
    now()
  );