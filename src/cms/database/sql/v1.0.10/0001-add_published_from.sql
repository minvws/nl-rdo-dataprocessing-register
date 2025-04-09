alter table
  "algorithm_records"
add
  column "public_from" timestamp(0) without time zone null;

alter table
  "avg_processor_processing_records"
add
  column "public_from" timestamp(0) without time zone null;

alter table
  "avg_responsible_processing_records"
add
  column "public_from" timestamp(0) without time zone null;

alter table
  "wpg_processing_records"
add
  column "public_from" timestamp(0) without time zone null;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_02_29_091853_add_published_from"',
    now(),
    now()
  );