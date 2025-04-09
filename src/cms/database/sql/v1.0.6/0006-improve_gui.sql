alter table
  "avg_responsible_processing_records"
drop
  column "responsibility_distribution";

alter table
  "avg_responsible_processing_records"
drop
  column "pseudonymization";

alter table
  "avg_responsible_processing_records"
drop
  column "encryption";

alter table
  "avg_responsible_processing_records"
drop
  column "electronic_way";

alter table
  "avg_responsible_processing_records"
drop
  column "access";

alter table
  "avg_responsible_processing_records"
drop
  column "safety_processors";

alter table
  "avg_responsible_processing_records"
drop
  column "safety_responsibles";

alter table
  "avg_responsible_processing_records"
drop
  column "measures";

alter table
  "avg_responsible_processing_records"
drop
  column "security";

alter table
  "avg_responsible_processing_records"
drop
  column "outside_eu_description";

alter table
  "avg_responsible_processing_records"
drop
  column "outside_eu_protection_level";

alter table
  "avg_responsible_processing_records"
drop
  column "outside_eu_protection_level_description";

alter table
  "avg_responsible_processing_records"
add
  column "has_processors" boolean not null default '0';

alter table
  "avg_responsible_processing_records"
add
  column "has_security" boolean not null default '0';

alter table
  "avg_responsible_processing_records"
add
  column "has_systems" boolean not null default '0';

alter table
  "avg_responsible_processing_records"
add
  column "responsibility_distribution" text null;

alter table
  "avg_responsible_processing_records"
add
  column "pseudonymization" text null;

alter table
  "avg_responsible_processing_records"
add
  column "encryption" text null;

alter table
  "avg_responsible_processing_records"
add
  column "electronic_way" text null;

alter table
  "avg_responsible_processing_records"
add
  column "access" text null;

alter table
  "avg_responsible_processing_records"
add
  column "safety_processors" text null;

alter table
  "avg_responsible_processing_records"
add
  column "safety_responsibles" text null;

alter table
  "avg_responsible_processing_records"
add
  column "measures" text null;

alter table
  "avg_responsible_processing_records"
add
  column "security" text null;

alter table
  "avg_responsible_processing_records"
add
  column "outside_eu_description" text null;

alter table
  "avg_responsible_processing_records"
add
  column "outside_eu_protection_level" text null;

alter table
  "avg_responsible_processing_records"
add
  column "outside_eu_protection_level_description" text null;

alter table
  "wpg_processing_records"
drop
  column "explanation_available";

alter table
  "wpg_processing_records"
drop
  column "explanation_provisioning";

alter table
  "wpg_processing_records"
drop
  column "explanation_transfer";

alter table
  "wpg_processing_records"
drop
  column "logic";

alter table
  "wpg_processing_records"
drop
  column "consequences";

alter table
  "wpg_processing_records"
drop
  column "pseudonymization";

alter table
  "wpg_processing_records"
drop
  column "encryption";

alter table
  "wpg_processing_records"
drop
  column "electronic_way";

alter table
  "wpg_processing_records"
drop
  column "access";

alter table
  "wpg_processing_records"
drop
  column "safety_processors";

alter table
  "wpg_processing_records"
drop
  column "safety_responsibles";

alter table
  "wpg_processing_records"
drop
  column "measures";

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
  column "explanation_available" text null;

alter table
  "wpg_processing_records"
add
  column "explanation_provisioning" text null;

alter table
  "wpg_processing_records"
add
  column "explanation_transfer" text null;

alter table
  "wpg_processing_records"
add
  column "logic" text null;

alter table
  "wpg_processing_records"
add
  column "consequences" text null;

alter table
  "wpg_processing_records"
add
  column "pseudonymization" text null;

alter table
  "wpg_processing_records"
add
  column "encryption" text null;

alter table
  "wpg_processing_records"
add
  column "electronic_way" text null;

alter table
  "wpg_processing_records"
add
  column "access" text null;

alter table
  "wpg_processing_records"
add
  column "safety_processors" text null;

alter table
  "wpg_processing_records"
add
  column "safety_responsibles" text null;

alter table
  "wpg_processing_records"
add
  column "measures" text null;

alter table
  "wpg_processing_records"
add
  column "outside_eu_description" text null;

alter table
  "wpg_processing_records"
add
  column "outside_eu_adequate_protection_level" text null;

alter table
  "wpg_processing_records"
add
  column "outside_eu_adequate_protection_level_description" text null;

update
  "snapshots"
set
  "state" = 'established'
where
  ("state" = 'published');

update
  "snapshot_transitions"
set
  "state" = 'established'
where
  ("state" = 'published');