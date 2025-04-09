alter table
  "avg_processor_processing_records"
drop
  column "geb_pia";

alter table
  "avg_processor_processing_records"
drop
  column "encryption";

alter table
  "avg_processor_processing_records"
drop
  column "pseudonymization";

alter table
  "avg_processor_processing_records"
drop
  column "responsibility_distribution";

alter table
  "avg_processor_processing_records"
drop
  column "outside_eu_protection_level_description";

alter table
  "avg_processor_processing_records"
drop
  column "logic";

alter table
  "avg_processor_processing_records"
drop
  column "importance_consequences";

alter table
  "avg_processor_processing_records"
add
  column "geb_pia" varchar(255) not null default 'Onbekend';

alter table
  "avg_processor_processing_records"
add
  column "encryption" text not null default '';

alter table
  "avg_processor_processing_records"
add
  column "pseudonymization" text not null default '';

alter table
  "avg_processor_processing_records"
add
  column "responsibility_distribution" text not null default '';

alter table
  "avg_processor_processing_records"
add
  column "outside_eu_protection_level_description" text not null default '';

alter table
  "avg_processor_processing_records"
add
  column "logic" text not null default '';

alter table
  "avg_processor_processing_records"
add
  column "importance_consequences" text not null default '';

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_02_02_142643_fix_release"',
    now(),
    now()
  );