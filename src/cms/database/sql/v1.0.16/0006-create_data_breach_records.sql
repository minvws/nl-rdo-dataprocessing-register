ALTER TABLE
  avg_processor_processing_records ALTER COLUMN direct_access
SET
  DATA TYPE TEXT;

ALTER TABLE
  avg_processor_processing_records ALTER COLUMN electronic_way
SET
  DATA TYPE TEXT;

ALTER TABLE
  avg_processor_processing_records ALTER COLUMN measures
SET
  DATA TYPE TEXT;

create table "data_breach_records" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "number" varchar(255) not null,
  "name" varchar(255) not null,
  "type" varchar(255) not null,
  "reported_at" date null,
  "ap_reported" boolean not null,
  "discovered_at" date null,
  "started_at" date null,
  "ended_at" date null,
  "ap_reported_at" date null,
  "completed_at" date null,
  "nature_of_incident" text null,
  "nature_of_incident_other" text null,
  "summary" text null,
  "involved_people" text null,
  "personal_data_categories" text null,
  "personal_data_categories_other" text null,
  "personal_data_special_categories" text null,
  "estimated_risk" text null,
  "measures" text null,
  "reported_to_involved" boolean not null,
  "reported_to_involved_communication" text null,
  "reported_to_involved_communication_other" text null,
  "fg_reported" boolean not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null,
  "deleted_at" timestamp(0) without time zone null
);

ALTER TABLE
  "data_breach_records" owner TO "dpr";

alter table
  "data_breach_records"
add
  constraint "data_breach_records_organisation_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "data_breach_records"
add
  primary key ("id");

create index "data_breach_records_number_index" on "data_breach_records" ("number");

create index "data_breach_records_name_index" on "data_breach_records" ("name");

CREATE UNIQUE INDEX data_breach_records_number ON data_breach_records (number)
WHERE
  deleted_at IS NULL;

;

create table "data_breach_record_relatables" (
  "data_breach_record_id" uuid not null,
  "data_breach_record_relatable_type" varchar(255) not null,
  "data_breach_record_relatable_id" uuid not null
);

ALTER TABLE
  "data_breach_record_relatables" owner TO "dpr";

alter table
  "data_breach_record_relatables"
add
  constraint "data_breach_record_relatables_data_breach_record_id_foreign" foreign key ("data_breach_record_id") references "data_breach_records" ("id") on delete cascade;

create index "data_breach_record_relatables_data_breach_record_relatable_type_data_breach_record_relatable_id_index" on "data_breach_record_relatables" (
  "data_breach_record_relatable_type",
  "data_breach_record_relatable_id"
);

alter table
  "data_breach_record_relatables"
add
  constraint "data_breach_record_relatables_data_breach_record_id_data_breach_record_relatable_id_data_breach_record_relatable_type_unique" unique (
    "data_breach_record_id", "data_breach_record_relatable_id",
    "data_breach_record_relatable_type"
  );

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_04_09_083717_create_data_breach_records"',
    now(),
    now()
  );