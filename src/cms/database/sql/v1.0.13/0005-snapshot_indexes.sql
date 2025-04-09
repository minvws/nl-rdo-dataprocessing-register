create index "snapshots_name_index" on "snapshots" ("name");

DELETE FROM
  snapshots
WHERE
  snapshot_source_type IS NULL;

DELETE FROM
  snapshots
WHERE
  snapshot_source_id IS NULL;

ALTER TABLE
  snapshots ALTER COLUMN snapshot_source_type
SET
  NOT NULL;

ALTER TABLE
  snapshots ALTER COLUMN snapshot_source_id
SET
  NOT NULL;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_03_22_110659_snapshot_indexes"',
    now(),
    now()
  );