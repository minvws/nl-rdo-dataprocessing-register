create table "system_wpg_processing_record" (
  "system_id" uuid not null,
  "wpg_processing_record_id" uuid not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "system_wpg_processing_record" owner TO "dpr";

alter table
  "system_wpg_processing_record"
add
  constraint "system_wpg_processing_record_system_id_foreign" foreign key ("system_id") references "systems" ("id") on delete cascade;

alter table
  "system_wpg_processing_record"
add
  constraint "system_wpg_processing_record_wpg_processing_record_id_foreign" foreign key ("wpg_processing_record_id") references "wpg_processing_records" ("id") on delete cascade;