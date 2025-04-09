alter table
  "avg_processor_processing_records"
add
  column "country_other" text null;

alter table
  "avg_responsible_processing_records"
add
  column "country_other" text null;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_12_10_131421_eer_country_other"',
    now(),
    now()
  );