create table "domains" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "url" varchar(255) not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "domains" owner TO "dpr";

alter table
  "domains"
add
  constraint "domains_organisation_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "domains"
add
  primary key ("id");