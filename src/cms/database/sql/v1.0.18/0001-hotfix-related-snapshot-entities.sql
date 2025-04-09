delete from
  "related_snapshot_sources"
where
  "snapshot_source_type" in (
    'App\Models\Avg\AvgGoal', 'App\Models\Stakeholder',
    'App\Models\StakeholderDataItem',
    'App\Models\Avg\WpgGoal'
  );

ALTER TABLE
  snapshot_data
DROP
  constraint snapshot_data_snapshot_id_foreign;

ALTER TABLE
  snapshot_data
ADD
  constraint snapshot_data_snapshot_id_foreign foreign key (snapshot_id) references snapshots(id) on delete cascade;

ALTER TABLE
  snapshot_transitions
DROP
  constraint snapshot_transitions_snapshot_id_foreign;

ALTER TABLE
  snapshot_transitions
ADD
  constraint snapshot_transitions_snapshot_id_foreign foreign key (snapshot_id) references snapshots(id) on delete cascade;

delete from
  "snapshots"
where
  "snapshot_source_type" in (
    'App\Models\Avg\AvgGoal', 'App\Models\Stakeholder',
    'App\Models\StakeholderDataItem',
    'App\Models\Avg\WpgGoal'
  );

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_04_22_125025_hotfix-related-snapshot-entities"',
    now(),
    now()
  );