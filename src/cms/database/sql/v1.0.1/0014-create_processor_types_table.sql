create table "processor_types" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "name" varchar(255) not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "processor_types" owner TO "dpr";

alter table
  "processor_types"
add
  constraint "processor_types_organisation_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "processor_types"
add
  primary key ("id");

alter table
  "processors"
add
  column "processor_type_id" uuid null;

alter table
  "processors"
add
  constraint "processors_processor_type_id_foreign" foreign key ("processor_type_id") references "processor_types" ("id");