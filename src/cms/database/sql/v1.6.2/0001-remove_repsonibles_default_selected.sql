alter table
  "responsibles"
drop
  column "default_selected";

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2025_01_24_163438_remove_repsonibles_default_selected"',
    now(),
    now()
  );