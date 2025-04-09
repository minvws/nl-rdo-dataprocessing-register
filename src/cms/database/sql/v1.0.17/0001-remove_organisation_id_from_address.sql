alter table
  "addresses"
drop
  column "organisation_id";

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_04_15_090501_remove_organisation_id_from_address"',
    now(),
    now()
  );