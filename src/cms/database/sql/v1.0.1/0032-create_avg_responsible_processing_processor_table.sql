create table "avg_responsible_processing_record_processor" (
  "avg_responsible_processing_record_id" uuid not null,
  "processor_id" uuid not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "avg_responsible_processing_record_processor" owner TO "dpr";

alter table
  "avg_responsible_processing_record_processor"
add
  constraint "avg_responsible_processing_record_processor_avg_responsible_processing_record_id_foreign" foreign key (
    "avg_responsible_processing_record_id"
  ) references "avg_responsible_processing_records" ("id") on delete cascade;

alter table
  "avg_responsible_processing_record_processor"
add
  constraint "avg_responsible_processing_record_processor_processor_id_foreign" foreign key ("processor_id") references "processors" ("id") on delete cascade;