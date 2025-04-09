create table "avg_responsible_processing_records" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "name" varchar(255) not null,
  "text" text not null,
  "number" varchar(255) not null,
  "responsibility_distribution" text not null default '',
  "remarks" text not null default '',
  "pseudonymization" text not null,
  "encryption" text not null,
  "electronic_way" text not null,
  "access" text not null,
  "safety_processors" text not null,
  "safety_responsibles" text not null,
  "measures" text not null,
  "security" boolean not null default '0',
  "outside_eu" boolean not null default '0',
  "outside_eu_description" text not null default '',
  "outside_eu_protection_level" text not null default '',
  "outside_eu_protection_level_description" text not null default '',
  "decision_making" boolean not null default '0',
  "logic" text not null default '',
  "importance_consequences" text not null default '',
  "dpia" boolean not null default '0',
  "import_id" varchar(255) not null default '',
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "avg_responsible_processing_records" owner TO "dpr";

alter table
  "avg_responsible_processing_records"
add
  constraint "avg_responsible_processing_records_organisation_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "avg_responsible_processing_records"
add
  primary key ("id");

alter table
  "avg_responsible_processing_records"
add
  constraint "avg_responsible_processing_records_number_unique" unique ("number");