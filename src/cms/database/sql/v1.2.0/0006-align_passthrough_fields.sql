alter table
  "avg_processor_processing_records"
drop
  column "outside_eu_protection_level";

alter table
  "avg_processor_processing_records"
add
  column "outside_eu_protection_level" varchar(255) null;

alter table
  "avg_processor_processing_records"
add
  column "outside_eu_description" varchar(255) null;

alter table
  "avg_responsible_processing_records"
add
  column "country" varchar(255) null;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_05_22_143317_align_passthrough_fields"',
    now(),
    now()
  );