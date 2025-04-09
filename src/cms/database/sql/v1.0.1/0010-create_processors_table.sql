create table "processors" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "name" varchar(255) not null,
  "email" varchar(255) not null,
  "phone" varchar(255) not null,
  "import_id" varchar(255) null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "processors" owner TO "dpr";

alter table
  "processors"
add
  constraint "processors_organisation_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "processors"
add
  primary key ("id");