alter table
  "avg_processor_processing_records"
drop
  column "measures_description";

alter table
  "avg_processor_processing_records"
add
  column "measures_description" text null;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_01_25_095601_avg_processor_production_import"',
    now(),
    now()
  );