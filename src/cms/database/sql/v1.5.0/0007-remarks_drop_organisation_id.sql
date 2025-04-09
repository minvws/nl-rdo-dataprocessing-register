alter table
  "remarks"
drop
  column "organisation_id";

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_11_08_100409_remarks_drop_organisation_id"',
    now(),
    now()
  );