alter table
  "public_website_snapshot_entries" rename column "last_public_website_check_id" to "last_static_website_check_id";

alter table
  "public_website_checks" rename to "static_website_checks";

alter table
  "public_website_snapshot_entries" rename to "static_website_snapshot_entries";

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2025_09_11_142453_static_website_checks"',
    now(),
    now()
  );