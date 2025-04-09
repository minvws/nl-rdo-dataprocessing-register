alter table
  "avg_responsible_processing_records"
drop
  column "security";

alter table
  "avg_responsible_processing_records"
drop
  column "logic";

alter table
  "avg_responsible_processing_records"
drop
  column "importance_consequences";

alter table
  "avg_responsible_processing_records"
drop
  column "outside_eu_protection_level";

alter table
  "avg_responsible_processing_records"
add
  column "has_pseudonymization" boolean not null default '0';

alter table
  "avg_responsible_processing_records"
add
  column "has_encryption" boolean not null default '0';

alter table
  "avg_responsible_processing_records"
add
  column "access_description" text null;

alter table
  "avg_responsible_processing_records"
add
  column "logic" text null;

alter table
  "avg_responsible_processing_records"
add
  column "importance_consequences" text null;

alter table
  "avg_responsible_processing_records"
add
  column "outside_eu_protection_level" boolean not null default '0';

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_01_23_105114_fix_production_import"',
    now(),
    now()
  );