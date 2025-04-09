alter table
  "avg_processor_processing_records"
drop
  column "has_subprocessors";

alter table
  "avg_processor_processing_records"
add
  column "has_processors" boolean not null default '0';

alter table
  "avg_processor_processing_records"
add
  column "has_systems" boolean not null default '0';

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_04_03_115614_align_processor_processing_record"',
    now(),
    now()
  );