create table "contact_persons" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "name" varchar(255) not null,
  "role" varchar(255) not null,
  "email" varchar(255) null,
  "phone" varchar(255) null,
  "import_id" varchar(255) null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "contact_persons" owner TO "dpr";

alter table
  "contact_persons"
add
  constraint "contact_persons_organisation_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "contact_persons"
add
  primary key ("id");