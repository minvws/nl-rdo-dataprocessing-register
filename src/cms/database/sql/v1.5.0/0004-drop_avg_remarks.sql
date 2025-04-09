alter table
  "avg_responsible_processing_records"
drop
  column "remarks";

alter table
  "wpg_processing_records"
drop
  column "remarks";

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_11_01_161837_drop_avg_remarks"',
    now(),
    now()
  );