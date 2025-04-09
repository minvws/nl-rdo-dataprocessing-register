alter table
  "users"
drop
  column "current_organisation_id";

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_02_09_132405_fix_roles"',
    now(),
    now()
  );