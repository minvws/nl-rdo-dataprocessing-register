create table "categories" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "name" varchar(255) not null,
  "import_id" varchar(255) not null default '',
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "categories" owner TO "dpr";

alter table
  "categories"
add
  constraint "categories_organisation_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "categories"
add
  primary key ("id");