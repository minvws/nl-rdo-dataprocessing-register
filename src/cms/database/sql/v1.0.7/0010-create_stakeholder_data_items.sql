create table "stakeholder_data_items" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "import_id" varchar(255) null,
  "description" varchar(512) null,
  "collection_purpose" varchar(512) null,
  "retention_period" varchar(512) null,
  "is_source_stakeholder" varchar(255) not null default 'onbekend',
  "source_description" varchar(512) null,
  "is_stakeholder_mandatory" varchar(255) not null default 'onbekend',
  "stakeholder_consequences" varchar(512) null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null,
  "deleted_at" timestamp(0) without time zone null
);

ALTER TABLE
  "stakeholder_data_items" owner TO "dpr";

alter table
  "stakeholder_data_items"
add
  constraint "stakeholder_data_items_organisation_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "stakeholder_data_items"
add
  primary key ("id");

create table "stakeholder_stakeholder_data_item" (
  "stakeholder_data_item_id" uuid not null,
  "stakeholder_id" uuid not null
);

ALTER TABLE
  "stakeholder_stakeholder_data_item" owner TO "dpr";

alter table
  "stakeholder_stakeholder_data_item"
add
  constraint "sta_sta_dat_ite_stake_data_items_id_foreign" foreign key ("stakeholder_data_item_id") references "stakeholder_data_items" ("id") on delete cascade;

alter table
  "stakeholder_stakeholder_data_item"
add
  constraint "sta_sta_dat_ite_stakeholders_id_foreign" foreign key ("stakeholder_id") references "stakeholders" ("id") on delete cascade;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_01_31_115713_create_stakeholder_data_items"',
    now(),
    now()
  );