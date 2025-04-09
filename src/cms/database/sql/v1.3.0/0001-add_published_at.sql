alter table
  "avg_responsible_processing_records"
add
  column "published_at" timestamp(0) without time zone null;

alter table
  "organisations"
add
  column "published_at" timestamp(0) without time zone null;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_05_08_123616_add_published_at"',
    now(),
    now()
  );