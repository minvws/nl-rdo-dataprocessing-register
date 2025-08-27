alter table
  "user_organisation_roles" rename to "organisation_user_roles";

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2025_08_11_130705_organisation_user"',
    now(),
    now()
  );