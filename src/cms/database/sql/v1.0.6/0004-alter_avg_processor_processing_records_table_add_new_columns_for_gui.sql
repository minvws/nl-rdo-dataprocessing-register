ALTER TABLE
  avg_processor_processing_records ALTER responsibility_distribution
SET
  DEFAULT '';

ALTER TABLE
  avg_processor_processing_records ALTER pseudonymization
SET
  DEFAULT '';

ALTER TABLE
  avg_processor_processing_records ALTER encryption
SET
  DEFAULT '';

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
add
  column "has_subprocessors" boolean not null default '0';

alter table
  "avg_processor_processing_records"
add
  column "has_arrangements_with_responsibles" boolean not null default '0';

alter table
  "avg_processor_processing_records"
add
  column "has_arrangements_with_processors" boolean not null default '0';

alter table
  "avg_processor_processing_records"
add
  column "has_pseudonymization" boolean not null default '0';

alter table
  "avg_processor_processing_records"
add
  column "has_encryption" boolean not null default '0';

alter table
  "avg_processor_processing_records"
add
  column "has_goal" boolean not null default '0';

alter table
  "avg_processor_processing_records"
add
  column "has_involved" boolean not null default '0';

alter table
  "avg_processor_processing_records"
add
  column "suspects" boolean not null default '0';

alter table
  "avg_processor_processing_records"
add
  column "victims" boolean not null default '0';

alter table
  "avg_processor_processing_records"
add
  column "convicts" boolean not null default '0';

alter table
  "avg_processor_processing_records"
add
  column "third_parties" boolean not null default '0';

alter table
  "avg_processor_processing_records"
add
  column "third_parties_description" text not null default '0';

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
drop
  column "security";

alter table
  "avg_processor_processing_records"
drop
  column "safety_responsibles";

alter table
  "avg_processor_processing_records"
drop
  column "safety_processors";

alter table
  "avg_processor_processing_records"
drop
  column "access";

alter table
  "avg_processor_processing_records"
drop
  column "electronic_way";

alter table
  "avg_processor_processing_records"
drop
  column "measures";

alter table
  "avg_processor_processing_records"
drop
  column "remarks";

ALTER TABLE
  avg_processor_processing_records ALTER outside_eu_protection_level_description
SET
  DEFAULT '';

ALTER TABLE
  avg_processor_processing_records ALTER logic
SET
  DEFAULT '';

ALTER TABLE
  avg_processor_processing_records ALTER importance_consequences
SET
  DEFAULT '';

ALTER TABLE
  avg_processor_processing_records ALTER geb_pia TYPE TEXT;

ALTER TABLE
  avg_processor_processing_records ALTER geb_pia
SET
  DEFAULT '';

alter table
  "avg_processor_processing_records"
add
  column "has_security" boolean not null default '0';

alter table
  "avg_processor_processing_records"
add
  column "arrangements_with_responsibles_description" text null default '';

alter table
  "avg_processor_processing_records"
add
  column "arrangements_with_processors_description" text null default '';

alter table
  "avg_processor_processing_records"
add
  column "direct_access" json not null default '[]';

alter table
  "avg_processor_processing_records"
add
  column "direct_access_third_party_description" text not null default '';

alter table
  "avg_processor_processing_records"
add
  column "measures_description" text not null default '';

alter table
  "avg_processor_processing_records"
add
  column "electronic_way" json not null default '[]';

alter table
  "avg_processor_processing_records"
add
  column "measures" json not null default '[]';