alter table
  "responsibles"
drop
  column "email";

alter table
  "responsibles"
drop
  column "phone";

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_11_08_091510_drop_responsible_details"',
    now(),
    now()
  );