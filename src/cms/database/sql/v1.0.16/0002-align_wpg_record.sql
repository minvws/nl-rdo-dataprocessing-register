alter table
  "wpg_processing_records"
drop
  column "security";

alter table
  "wpg_processing_records"
drop
  column "safety_responsibles";

alter table
  "wpg_processing_records"
drop
  column "outside_eu";

alter table
  "wpg_processing_records"
drop
  column "outside_eu_description";

alter table
  "wpg_processing_records"
drop
  column "outside_eu_adequate_protection_level";

alter table
  "wpg_processing_records"
drop
  column "outside_eu_adequate_protection_level_description";

alter table
  "wpg_processing_records"
add
  column "has_processors" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "has_systems" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "has_security" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "has_encryption" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "has_pseudonymization" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "article_23" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "article_24" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "police_none" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "police_race_or_ethnicity" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "police_political_attitude" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "police_faith_or_belief" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "police_association_membership" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "police_genetic" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "police_identification" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "police_health" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "police_sexual_life" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "police_justice" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "measures_description" text null;

alter table
  "wpg_processing_records"
add
  column "access_description" text null;

ALTER TABLE
  wpg_processing_records ALTER COLUMN third_party_explanation
SET
  DATA TYPE text;

alter table
  "wpg_goals"
add
  column "remarks" text null;

alter table
  "wpg_goals"
add
  column "sort" integer not null default '0';

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_04_05_090743_align_wpg_record"',
    now(),
    now()
  );