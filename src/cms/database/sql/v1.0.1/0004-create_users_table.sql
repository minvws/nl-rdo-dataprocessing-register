create table "users" (
  "id" uuid not null,
  "current_organisation_id" uuid null,
  "name" varchar(255) not null,
  "email" varchar(255) not null,
  "email_verified_at" timestamp(0) without time zone null,
  "password" varchar(255) not null,
  "remember_token" varchar(100) null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "users" owner TO "dpr";

alter table
  "users"
add
  constraint "users_current_organisation_id_foreign" foreign key ("current_organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "users"
add
  primary key ("id");

alter table
  "users"
add
  constraint "users_email_unique" unique ("email");