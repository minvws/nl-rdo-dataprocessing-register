drop
  table if exists "processing_records";

alter table
  "tags"
drop
  column "type";

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_01_16_141422_drop_processing_records"',
    now(),
    now()
  );