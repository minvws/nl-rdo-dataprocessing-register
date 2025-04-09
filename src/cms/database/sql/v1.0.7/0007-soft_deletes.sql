alter table
  "algorithm_meta_schemas"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "algorithm_publication_categories"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "algorithm_records"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "algorithm_statuses"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "algorithm_themes"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "avg_goals"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "avg_goals"
drop
  constraint "avg_goals_organisa_id_import_id_unique";

CREATE UNIQUE INDEX avg_goals_organisation_import ON avg_goals (organisation_id, import_id)
WHERE
  deleted_at IS NULL;

;

alter table
  "avg_goal_legal_bases"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "avg_processors"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "avg_processors"
drop
  constraint "avg_processo_organisa_id_import_id_unique";

CREATE UNIQUE INDEX avg_processors_organisation_import ON avg_processors (organisation_id, import_id)
WHERE
  deleted_at IS NULL;

;

alter table
  "avg_processor_processing_records"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "avg_processor_processing_records"
drop
  constraint "avg_pro_pro_rec_organisa_id_import_id_unique";

CREATE UNIQUE INDEX avg_processor_processing_records_organisation_import ON avg_processor_processing_records (organisation_id, import_id)
WHERE
  deleted_at IS NULL;

;

alter table
  "avg_processor_processing_records"
drop
  constraint "avg_processor_processing_records_number_unique";

CREATE UNIQUE INDEX avg_processor_processing_records_number ON avg_processor_processing_records (number)
WHERE
  deleted_at IS NULL;

;

alter table
  "avg_processor_processing_record_service"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "avg_responsible_processing_records"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "avg_responsible_processing_records"
drop
  constraint "avg_res_pro_rec_organisa_id_import_id_unique";

CREATE UNIQUE INDEX avg_responsible_processing_records_organisation_import ON avg_responsible_processing_records (organisation_id, import_id)
WHERE
  deleted_at IS NULL;

;

alter table
  "avg_responsible_processing_records"
drop
  constraint "avg_responsible_processing_records_number_unique";

CREATE UNIQUE INDEX avg_responsible_processing_records_number ON avg_responsible_processing_records (number)
WHERE
  deleted_at IS NULL;

;

alter table
  "avg_responsible_processing_record_service"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "wpg_goals"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "wpg_goals"
drop
  constraint "wpg_goals_organisa_id_import_id_unique";

CREATE UNIQUE INDEX wpg_goals_organisation_import ON wpg_goals (organisation_id, import_id)
WHERE
  deleted_at IS NULL;

;

alter table
  "wpg_processing_records"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "wpg_processing_records"
drop
  constraint "wpg_proce_recor_organisa_id_import_id_unique";

CREATE UNIQUE INDEX wpg_processing_records_organisation_import ON wpg_processing_records (organisation_id, import_id)
WHERE
  deleted_at IS NULL;

;

alter table
  "wpg_processing_records"
drop
  constraint "wpg_processi_records_number_unique";

CREATE UNIQUE INDEX wpg_processing_records_number ON wpg_processing_records (number)
WHERE
  deleted_at IS NULL;

;

alter table
  "wpg_processing_record_service"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "addresses"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "categories"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "categories"
drop
  constraint "categories_organisa_id_import_id_unique";

CREATE UNIQUE INDEX categories_organisation_import ON categories (organisation_id, import_id)
WHERE
  deleted_at IS NULL;

;

alter table
  "contact_persons"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "contact_persons"
drop
  constraint "contact_persons_organisa_id_import_id_unique";

CREATE UNIQUE INDEX contact_persons_organisation_import ON contact_persons (organisation_id, import_id)
WHERE
  deleted_at IS NULL;

;

alter table
  "contact_person_positions"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "domains"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "organisations"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "processors"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "processors"
drop
  constraint "processors_organisa_id_import_id_unique";

CREATE UNIQUE INDEX processors_organisation_import ON processors (organisation_id, import_id)
WHERE
  deleted_at IS NULL;

;

alter table
  "processor_types"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "receivers"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "receivers"
drop
  constraint "receivers_organisa_id_import_id_unique";

CREATE UNIQUE INDEX receivers_organisation_import ON receivers (organisation_id, import_id)
WHERE
  deleted_at IS NULL;

;

alter table
  "remarks"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "responsibles"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "responsibles"
drop
  constraint "responsibles_organisa_id_import_id_unique";

CREATE UNIQUE INDEX responsibles_organisation_import ON responsibles (organisation_id, import_id)
WHERE
  deleted_at IS NULL;

;

alter table
  "responsible_types"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "systems"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "systems"
drop
  constraint "systems_organisa_id_import_id_unique";

CREATE UNIQUE INDEX systems_organisation_import ON systems (organisation_id, import_id)
WHERE
  deleted_at IS NULL;

;

alter table
  "tags"
add
  column "deleted_at" timestamp(0) without time zone null;

alter table
  "users"
add
  column "deleted_at" timestamp(0) without time zone null;

CREATE UNIQUE INDEX algorithm_records_number ON algorithm_records (number)
WHERE
  deleted_at IS NULL;

;

alter table
  "avg_processor_processing_records"
drop
  column "geb_pia";

alter table
  "avg_processor_processing_records"
add
  column "geb_pia" text null;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_01_29_110130_soft_deletes"',
    now(),
    now()
  );