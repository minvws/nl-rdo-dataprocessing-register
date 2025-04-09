alter table
  "organisations"
drop
  constraint "organisations_slug_unique";

CREATE UNIQUE INDEX organisations_slug ON organisations (slug)
WHERE
  deleted_at IS NULL;

;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_03_22_143507_organisation_slug_unique_deleted_at"',
    now(),
    now()
  );