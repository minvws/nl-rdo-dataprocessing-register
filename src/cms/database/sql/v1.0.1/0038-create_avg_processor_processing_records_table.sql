create table "avg_processor_processing_records" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "name" varchar(255) not null,
  "number" varchar(255) not null,
  "service" varchar(255) not null,
  "responsibility_distribution" text not null,
  "remarks" text not null,
  "pseudonymization" text not null,
  "encryption" text not null,
  "electronic_way" text not null,
  "access" text not null,
  "safety_processors" text not null,
  "safety_responsibles" text not null,
  "measures" text not null,
  "security" boolean not null,
  "outside_eu" boolean not null,
  "country" varchar(255) null,
  "outside_eu_protection_level" text not null,
  "outside_eu_protection_level_description" text not null,
  "decision_making" boolean not null,
  "logic" text not null,
  "importance_consequences" text not null,
  "geb_pia" boolean not null,
  "import_id" varchar(255) null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "avg_processor_processing_records" owner TO "dpr";

alter table
  "avg_processor_processing_records"
add
  constraint "avg_processor_processing_records_organisation_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "avg_processor_processing_records"
add
  primary key ("id");

alter table
  "avg_processor_processing_records"
add
  constraint "avg_processor_processing_records_number_unique" unique ("number");