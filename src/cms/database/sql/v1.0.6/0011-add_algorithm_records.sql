create table "algorithm_themes" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "name" text not null,
  "enabled" boolean not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "algorithm_themes" owner TO "dpr";

alter table
  "algorithm_themes"
add
  constraint "algorith_themes_organisations_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "algorithm_themes"
add
  primary key ("id");

create index "algorithm_themes_name_index" on "algorithm_themes" ("name");

create table "algorithm_statuses" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "name" text not null,
  "enabled" boolean not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "algorithm_statuses" owner TO "dpr";

alter table
  "algorithm_statuses"
add
  constraint "algorith_statuses_organisations_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "algorithm_statuses"
add
  primary key ("id");

create index "algorithm_statuses_name_index" on "algorithm_statuses" ("name");

create table "algorithm_publication_categories" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "name" text not null,
  "enabled" boolean not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "algorithm_publication_categories" owner TO "dpr";

alter table
  "algorithm_publication_categories"
add
  constraint "algor_publi_categ_organisations_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "algorithm_publication_categories"
add
  primary key ("id");

create index "algorithm_publication_categories_name_index" on "algorithm_publication_categories" ("name");

create table "algorithm_meta_schemas" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "name" text not null,
  "enabled" boolean not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "algorithm_meta_schemas" owner TO "dpr";

alter table
  "algorithm_meta_schemas"
add
  constraint "algor_meta_schem_organisations_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "algorithm_meta_schemas"
add
  primary key ("id");

create index "algorithm_meta_schemas_name_index" on "algorithm_meta_schemas" ("name");

create table "algorithm_records" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "algorithm_theme_id" uuid null,
  "algorithm_status_id" uuid null,
  "algorithm_publication_category_id" uuid null,
  "algorithm_meta_schema_id" uuid null,
  "import_id" varchar(255) null,
  "name" varchar(255) not null,
  "number" varchar(255) not null,
  "description" text null,
  "start_date" date null,
  "end_date" date null,
  "contact_data" varchar(255) null,
  "source_link" varchar(255) null,
  "resp_goal_and_impact" text null,
  "resp_considerations" text null,
  "resp_human_intervention" text null,
  "resp_risk_analysis" text null,
  "resp_legal_base" text null,
  "resp_legal_base_link" varchar(255) null,
  "resp_processor_registry_link" varchar(255) null,
  "resp_impact_tests" text null,
  "resp_impact_test_links" text null,
  "resp_impact_tests_description" text null,
  "oper_data" text null,
  "oper_links" text null,
  "oper_technical_operation" text null,
  "oper_supplier" text null,
  "oper_source_code_link" varchar(255) null,
  "meta_lang" varchar(255) null,
  "meta_national_id" varchar(255) null,
  "meta_source_id" varchar(255) null,
  "meta_tags" text null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "algorithm_records" owner TO "dpr";

alter table
  "algorithm_records"
add
  constraint "algorith_records_organisations_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "algorithm_records"
add
  constraint "algorith_records_algorith_themes_id_foreign" foreign key ("algorithm_theme_id") references "algorithm_themes" ("id");

alter table
  "algorithm_records"
add
  constraint "algorith_records_algorith_statuses_id_foreign" foreign key ("algorithm_status_id") references "algorithm_statuses" ("id");

alter table
  "algorithm_records"
add
  constraint "algorith_records_algor_publi_categ_id_foreign" foreign key (
    "algorithm_publication_category_id"
  ) references "algorithm_publication_categories" ("id");

alter table
  "algorithm_records"
add
  constraint "algorith_records_algor_meta_schem_id_foreign" foreign key ("algorithm_meta_schema_id") references "algorithm_meta_schemas" ("id");

alter table
  "algorithm_records"
add
  primary key ("id");

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_01_11_132832_add_algorithm_records"',
    now(),
    now()
  );