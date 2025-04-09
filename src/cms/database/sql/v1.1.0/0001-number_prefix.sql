create table "entity_numbers" (
  "id" uuid not null,
  "type" varchar(255) not null,
  "number" varchar(255) not null
);

ALTER TABLE
  "entity_numbers" owner TO "dpr";

alter table
  "entity_numbers"
add
  constraint "entity_numbers_type_number_unique" unique ("type", "number");

alter table
  "entity_numbers"
add
  primary key ("id");

create table "entity_number_counters" (
  "id" uuid not null,
  "type" varchar(255) not null,
  "prefix" varchar(255) not null,
  "number" integer not null default '1'
);

ALTER TABLE
  "entity_number_counters" owner TO "dpr";

alter table
  "entity_number_counters"
add
  constraint "entity_number_counters_type_prefix_unique" unique ("type", "prefix");

alter table
  "entity_number_counters"
add
  primary key ("id");

alter table
  "organisations"
add
  column "register_entity_number_counter_id" varchar(255) null;

alter table
  "organisations"
add
  column "databreach_entity_number_counter_id" varchar(255) null;

alter table
  "organisations"
add
  constraint "organisations_register_entity_number_counter_id_unique" unique (
    "register_entity_number_counter_id"
  );

alter table
  "organisations"
add
  constraint "organisations_databreach_entity_number_counter_id_unique" unique (
    "databreach_entity_number_counter_id"
  );

alter table
  "algorithm_records"
add
  column "entity_number_id" uuid null;

alter table
  "algorithm_records"
add
  constraint "algorithm_records_entity_number_id_foreign" foreign key ("entity_number_id") references "entity_numbers" ("id") on delete cascade;

ALTER TABLE
  algorithm_records ALTER COLUMN number
DROP
  NOT NULL;

;

alter table
  "data_breach_records"
add
  column "entity_number_id" uuid null;

alter table
  "data_breach_records"
add
  constraint "data_breach_records_entity_number_id_foreign" foreign key ("entity_number_id") references "entity_numbers" ("id") on delete cascade;

ALTER TABLE
  data_breach_records ALTER COLUMN number
DROP
  NOT NULL;

;

alter table
  "avg_processor_processing_records"
add
  column "entity_number_id" uuid null;

alter table
  "avg_processor_processing_records"
add
  constraint "avg_processor_processing_records_entity_number_id_foreign" foreign key ("entity_number_id") references "entity_numbers" ("id") on delete cascade;

ALTER TABLE
  avg_processor_processing_records ALTER COLUMN number
DROP
  NOT NULL;

;

alter table
  "avg_responsible_processing_records"
add
  column "entity_number_id" uuid null;

alter table
  "avg_responsible_processing_records"
add
  constraint "avg_responsible_processing_records_entity_number_id_foreign" foreign key ("entity_number_id") references "entity_numbers" ("id") on delete cascade;

ALTER TABLE
  avg_responsible_processing_records ALTER COLUMN number
DROP
  NOT NULL;

;

alter table
  "wpg_processing_records"
add
  column "entity_number_id" uuid null;

alter table
  "wpg_processing_records"
add
  constraint "wpg_processing_records_entity_number_id_foreign" foreign key ("entity_number_id") references "entity_numbers" ("id") on delete cascade;

ALTER TABLE
  wpg_processing_records ALTER COLUMN number
DROP
  NOT NULL;

;

alter table
  "avg_processor_processing_records"
add
  column "import_number" varchar(255) null;

create index "avg_processor_processing_records_import_number_index" on "avg_processor_processing_records" ("import_number");

alter table
  "avg_responsible_processing_records"
add
  column "import_number" varchar(255) null;

create index "avg_responsible_processing_records_import_number_index" on "avg_responsible_processing_records" ("import_number");

alter table
  "wpg_processing_records"
add
  column "import_number" varchar(255) null;

create index "wpg_processing_records_import_number_index" on "wpg_processing_records" ("import_number");

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_04_19_113004_number_prefix"',
    now(),
    now()
  );