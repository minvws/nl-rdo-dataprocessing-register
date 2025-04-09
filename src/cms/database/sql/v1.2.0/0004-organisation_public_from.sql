alter table
  "organisations"
add
  column "public_from" timestamp(0) without time zone null;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_05_02_145042_organisation_public_from"',
    now(),
    now()
  );