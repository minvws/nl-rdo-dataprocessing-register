create table "avg_responsible_processing_record_responsible" (
  "avg_responsible_processing_record_id" uuid not null,
  "responsible_id" uuid not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "avg_responsible_processing_record_responsible" owner TO "dpr";

alter table
  "avg_responsible_processing_record_responsible"
add
  constraint "avg_responsible_processing_record_responsible_avg_responsible_processing_record_id_foreign" foreign key (
    "avg_responsible_processing_record_id"
  ) references "avg_responsible_processing_records" ("id") on delete cascade;

alter table
  "avg_responsible_processing_record_responsible"
add
  constraint "avg_responsible_processing_record_responsible_responsible_id_foreign" foreign key ("responsible_id") references "responsibles" ("id") on delete cascade;