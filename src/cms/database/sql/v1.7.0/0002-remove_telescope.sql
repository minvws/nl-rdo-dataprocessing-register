drop
  table if exists "telescope_entries_tags";

drop
  table if exists "telescope_entries";

drop
  table if exists "telescope_monitoring";

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2025_02_12_143839_remove_telescope"',
    now(),
    now()
  );