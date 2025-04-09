truncate "avg_processor_processing_records" restart identity cascade;

truncate "avg_responsible_processing_records" restart identity cascade;

truncate "wpg_processing_records" restart identity cascade;

truncate "avg_processors" restart identity cascade;

truncate "categories" restart identity cascade;

truncate "contact_persons" restart identity cascade;

truncate "processors" restart identity cascade;

truncate "receivers" restart identity cascade;

truncate "responsibles" restart identity cascade;

truncate "systems" restart identity cascade;

truncate "wpg_goals" restart identity cascade;

alter table
  "avg_goals"
add
  column "import_id" varchar(255) null;

alter table
  "avg_goals"
add
  constraint "avg_goals_organisa_id_import_id_unique" unique ("organisation_id", "import_id");

alter table
  "avg_processor_processing_records"
drop
  column "outside_eu_protection_level";

alter table
  "avg_processor_processing_records"
add
  column "outside_eu_protection_level" boolean not null default '0';

alter table
  "avg_processor_processing_records"
add
  constraint "avg_pro_pro_rec_organisa_id_import_id_unique" unique ("organisation_id", "import_id");

alter table
  "avg_processors"
add
  column "import_id" varchar(255) null;

alter table
  "avg_processors"
add
  constraint "avg_processo_organisa_id_import_id_unique" unique ("organisation_id", "import_id");

alter table
  "avg_responsible_processing_records"
drop
  column "import_id";

alter table
  "avg_responsible_processing_records"
add
  column "service" varchar(255) null;

alter table
  "avg_responsible_processing_records"
add
  column "import_id" varchar(255) null;

alter table
  "avg_responsible_processing_records"
add
  constraint "avg_res_pro_rec_organisa_id_import_id_unique" unique ("organisation_id", "import_id");

alter table
  "avg_responsible_processing_records"
drop
  column "text";

alter table
  "categories"
drop
  column "import_id";

alter table
  "categories"
add
  column "import_id" varchar(255) null;

alter table
  "categories"
add
  constraint "categories_organisa_id_import_id_unique" unique ("organisation_id", "import_id");

alter table
  "contact_persons"
add
  constraint "contact_persons_organisa_id_import_id_unique" unique ("organisation_id", "import_id");

alter table
  "processors"
add
  constraint "processors_organisa_id_import_id_unique" unique ("organisation_id", "import_id");

alter table
  "receivers"
drop
  column "name";

alter table
  "receivers"
add
  column "description" text null;

alter table
  "receivers"
add
  constraint "receivers_organisa_id_import_id_unique" unique ("organisation_id", "import_id");

alter table
  "responsibles"
add
  constraint "responsibles_organisa_id_import_id_unique" unique ("organisation_id", "import_id");

alter table
  "systems"
drop
  column "name";

alter table
  "systems"
add
  column "description" text null;

alter table
  "systems"
add
  constraint "systems_organisa_id_import_id_unique" unique ("organisation_id", "import_id");

alter table
  "wpg_goals"
add
  constraint "wpg_goals_organisa_id_import_id_unique" unique ("organisation_id", "import_id");

alter table
  "wpg_processing_records"
add
  column "none_available" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "no_provisioning" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "no_transfer" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "article_15" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "article_15_a" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "article_16" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "article_17" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "article_17_a" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "article_18" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "article_19" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "article_20" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  column "article_22" boolean not null default '0';

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
  column "geb_pia" boolean not null default '0';

alter table
  "wpg_processing_records"
add
  constraint "wpg_proce_recor_organisa_id_import_id_unique" unique ("organisation_id", "import_id");