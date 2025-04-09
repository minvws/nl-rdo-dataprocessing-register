create table "stakeholders" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "import_id" varchar(255) null,
  "description" varchar(512) null,
  "biometric" boolean not null default '0',
  "faith_or_belief" boolean not null default '0',
  "genetic" boolean not null default '0',
  "health" boolean not null default '0',
  "no_special_collected_data" boolean not null default '0',
  "political_attitude" boolean not null default '0',
  "race_or_ethnicity" boolean not null default '0',
  "sexual_life" boolean not null default '0',
  "trade_association_membership" boolean not null default '0',
  "criminal_law" boolean not null default '0',
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null,
  "deleted_at" timestamp(0) without time zone null
);

ALTER TABLE
  "stakeholders" owner TO "dpr";

alter table
  "stakeholders"
add
  constraint "stakeholders_organisation_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "stakeholders"
add
  primary key ("id");

create table "stakeholder_relatables" (
  "stakeholder_id" uuid not null,
  "stakeholder_relatable_type" varchar(255) not null,
  "stakeholder_relatable_id" uuid not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null,
  "deleted_at" timestamp(0) without time zone null
);

ALTER TABLE
  "stakeholder_relatables" owner TO "dpr";

alter table
  "stakeholder_relatables"
add
  constraint "stakehol_relatabl_stakeholders_id_foreign" foreign key ("stakeholder_id") references "stakeholders" ("id") on delete cascade;

create index "stakehol_relatabl_stake_relat_type_stake_relat_id_index" on "stakeholder_relatables" (
  "stakeholder_relatable_type", "stakeholder_relatable_id"
);

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_01_30_101330_create_stakeholders"',
    now(),
    now()
  );