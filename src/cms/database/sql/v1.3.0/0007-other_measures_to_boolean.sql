alter table
  "avg_processor_processing_records"
drop
  column "other_measures";

alter table
  "avg_responsible_processing_records"
drop
  column "other_measures";

alter table
  "wpg_processing_records"
drop
  column "other_measures";

alter table
  "avg_processor_processing_records"
add
  column "other_measures" boolean not null default '0';

alter table
  "avg_responsible_processing_records"
add
  column "other_measures" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "other_measures" boolean not null default '0';

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_05_29_085401_other_measures_to_boolean"',
    now(),
    now()
  );