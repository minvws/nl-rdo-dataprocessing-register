alter table
  "avg_processor_processing_records"
drop
  column "measures";

alter table
  "avg_processor_processing_records"
drop
  column "has_arrangements_with_responsibles";

alter table
  "avg_processor_processing_records"
drop
  column "arrangements_with_responsibles_description";

alter table
  "avg_processor_processing_records"
drop
  column "has_arrangements_with_processors";

alter table
  "avg_processor_processing_records"
drop
  column "arrangements_with_processors_description";

alter table
  "avg_processor_processing_records"
drop
  column "direct_access";

alter table
  "avg_processor_processing_records"
drop
  column "direct_access_third_party_description";

alter table
  "avg_processor_processing_records"
drop
  column "electronic_way";

alter table
  "avg_processor_processing_records"
drop
  column "has_encryption";

alter table
  "avg_processor_processing_records"
drop
  column "encryption";

alter table
  "avg_processor_processing_records"
add
  column "measures_implemented" boolean not null default '0';

alter table
  "avg_processor_processing_records"
add
  column "other_measures" text null;

alter table
  "avg_responsible_processing_records"
drop
  column "measures";

alter table
  "avg_responsible_processing_records"
drop
  column "safety_processors";

alter table
  "avg_responsible_processing_records"
drop
  column "access";

alter table
  "avg_responsible_processing_records"
drop
  column "access_description";

alter table
  "avg_responsible_processing_records"
drop
  column "electronic_way";

alter table
  "avg_responsible_processing_records"
drop
  column "has_encryption";

alter table
  "avg_responsible_processing_records"
drop
  column "safety_responsibles";

alter table
  "avg_responsible_processing_records"
drop
  column "encryption";

alter table
  "avg_responsible_processing_records"
add
  column "measures_implemented" boolean not null default '0';

alter table
  "avg_responsible_processing_records"
add
  column "other_measures" text null;

alter table
  "wpg_processing_records"
drop
  column "measures";

alter table
  "wpg_processing_records"
drop
  column "safety_processors";

alter table
  "wpg_processing_records"
drop
  column "access";

alter table
  "wpg_processing_records"
drop
  column "access_description";

alter table
  "wpg_processing_records"
drop
  column "electronic_way";

alter table
  "wpg_processing_records"
drop
  column "has_encryption";

alter table
  "wpg_processing_records"
drop
  column "encryption";

alter table
  "wpg_processing_records"
add
  column "measures_implemented" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "other_measures" text null;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_05_27_085808_refactor_measures"',
    now(),
    now()
  );