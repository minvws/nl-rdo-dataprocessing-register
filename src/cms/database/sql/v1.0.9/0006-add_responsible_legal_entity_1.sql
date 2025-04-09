create table "responsible_legal_entity" (
  "id" uuid not null,
  "name" varchar(255) not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null,
  "deleted_at" timestamp(0) without time zone null
);

ALTER TABLE
  "responsible_legal_entity" owner TO "dpr";

alter table
  "responsible_legal_entity"
add
  primary key ("id");

create index "responsible_legal_entity_name_index" on "responsible_legal_entity" ("name");

alter table
  "organisations"
add
  column "responsible_legal_entity_id" uuid null;

alter table
  "organisations"
add
  constraint "organisations_responsible_legal_entity_id_foreign" foreign key ("responsible_legal_entity_id") references "responsible_legal_entity" ("id");

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_03_01_094111_add_responsible_legal_entity_1"',
    now(),
    now()
  );