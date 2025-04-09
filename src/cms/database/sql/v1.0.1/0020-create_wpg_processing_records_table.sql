create table "wpg_processing_records" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "responsible_id" uuid null,
  "contact_person_id" uuid null,
  "name" varchar(255) not null,
  "number" varchar(255) not null,
  "service" varchar(255) not null,
  "remarks" text null,
  "import_id" varchar(255) null,
  "suspects" boolean not null default '0',
  "victims" boolean not null default '0',
  "convicts" boolean not null default '0',
  "third_parties" boolean not null default '0',
  "third_party_explanation" varchar(255) null,
  "pseudonymization" varchar(255) not null,
  "encryption" varchar(255) not null,
  "electronic_way" text not null,
  "access" text not null,
  "safety_processors" text not null,
  "safety_responsibles" text not null,
  "measures" text not null,
  "security" boolean not null default '0',
  "outside_eu" boolean not null default '0',
  "outside_eu_description" varchar(255) not null,
  "outside_eu_adequate_protection_level" varchar(255) not null,
  "outside_eu_adequate_protection_level_description" varchar(255) not null,
  "decision_making" boolean not null default '0',
  "logic" varchar(255) not null,
  "consequences" varchar(255) not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "wpg_processing_records" owner TO "dpr";

alter table
  "wpg_processing_records"
add
  constraint "wpg_processing_records_organisation_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "wpg_processing_records"
add
  constraint "wpg_processing_records_responsible_id_foreign" foreign key ("responsible_id") references "responsibles" ("id") on delete cascade;

alter table
  "wpg_processing_records"
add
  constraint "wpg_processing_records_contact_person_id_foreign" foreign key ("contact_person_id") references "contact_persons" ("id") on delete cascade;

alter table
  "wpg_processing_records"
add
  primary key ("id");