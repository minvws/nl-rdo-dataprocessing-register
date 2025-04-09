create table "processing_records" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "name" varchar(255) not null,
  "type" varchar(255) not null,
  "description" varchar(255) null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "processing_records" owner TO "dpr";

alter table
  "processing_records"
add
  constraint "processing_records_organisation_id_foreign" foreign key ("organisation_id") references "organisations" ("id");

alter table
  "processing_records"
add
  primary key ("id");