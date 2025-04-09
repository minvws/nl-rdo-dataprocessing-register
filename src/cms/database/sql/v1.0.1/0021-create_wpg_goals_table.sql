create table "wpg_goals" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "wpg_processing_record_id" uuid not null,
  "description" varchar(255) not null,
  "article_8" boolean not null default '0',
  "article_9" boolean not null default '0',
  "article_10_1a" boolean not null default '0',
  "article_10_1b" boolean not null default '0',
  "article_10_1c" boolean not null default '0',
  "article_12" boolean not null default '0',
  "article_13_1" boolean not null default '0',
  "article_13_2" boolean not null default '0',
  "article_13_3" boolean not null default '0',
  "explanation" text null,
  "import_id" varchar(255) null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "wpg_goals" owner TO "dpr";

alter table
  "wpg_goals"
add
  constraint "wpg_goals_organisation_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "wpg_goals"
add
  constraint "wpg_goals_wpg_processing_record_id_foreign" foreign key ("wpg_processing_record_id") references "wpg_processing_records" ("id") on delete cascade;

alter table
  "wpg_goals"
add
  primary key ("id");