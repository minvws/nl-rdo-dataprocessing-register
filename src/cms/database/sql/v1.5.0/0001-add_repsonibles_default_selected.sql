alter table
  "responsibles"
add
  column "default_selected" boolean not null default '0';

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_10_30_121709_add_repsonibles_default_selected"',
    now(),
    now()
  );