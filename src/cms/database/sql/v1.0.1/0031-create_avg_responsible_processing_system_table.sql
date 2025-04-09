create table "avg_responsible_processing_record_system" (
  "avg_responsible_processing_record_id" uuid not null,
  "system_id" uuid not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "avg_responsible_processing_record_system" owner TO "dpr";

alter table
  "avg_responsible_processing_record_system"
add
  constraint "avg_responsible_processing_record_system_avg_responsible_processing_record_id_foreign" foreign key (
    "avg_responsible_processing_record_id"
  ) references "avg_responsible_processing_records" ("id") on delete cascade;

alter table
  "avg_responsible_processing_record_system"
add
  constraint "avg_responsible_processing_record_system_system_id_foreign" foreign key ("system_id") references "systems" ("id") on delete cascade;