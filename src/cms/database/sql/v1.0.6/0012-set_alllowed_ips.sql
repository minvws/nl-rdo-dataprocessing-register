update
  "organisations"
set
  "allowed_ips" = '*.*.*.*';

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_01_18_100606_set_alllowed_ips"',
    now(),
    now()
  );