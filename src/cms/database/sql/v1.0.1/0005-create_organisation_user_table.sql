create table "organisation_user" (
  "organisation_id" uuid not null,
  "user_id" uuid not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "organisation_user" owner TO "dpr";

alter table
  "organisation_user"
add
  constraint "organisation_user_organisation_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "organisation_user"
add
  constraint "organisation_user_user_id_foreign" foreign key ("user_id") references "users" ("id") on delete cascade;