alter table
  "organisations"
add
  column "public_website_content" text null;

alter table
  "public_website"
drop
  column "organisation_content";

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_04_08_082034_public_website_organisation_content"',
    now(),
    now()
  );