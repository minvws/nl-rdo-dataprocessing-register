create table "avg_goals" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "goal" text not null,
  "legal_basis" varchar(255) not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "avg_goals" owner TO "dpr";

alter table
  "avg_goals"
add
  constraint "avg_goals_organisation_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "avg_goals"
add
  primary key ("id");