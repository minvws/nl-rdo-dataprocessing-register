alter table
  "wpg_processing_records"
drop
  column "none_available";

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_05_23_142002_general_feedback"',
    now(),
    now()
  );