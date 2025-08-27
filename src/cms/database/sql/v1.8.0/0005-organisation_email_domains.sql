alter table
  "organisations"
add
  column "allowed_email_domains" jsonb not null default '[]';

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2025_06_24_095237_organisation_email_domains"',
    now(),
    now()
  );