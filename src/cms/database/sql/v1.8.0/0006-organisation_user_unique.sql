alter table
  "organisation_user"
add
  constraint "organisation_user_organisation_id_user_id_unique" unique ("organisation_id", "user_id");

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2025_06_24_170728_organisation_user_unique"',
    now(),
    now()
  );