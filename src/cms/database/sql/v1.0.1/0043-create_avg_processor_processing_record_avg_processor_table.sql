create table "avg_processor_processing_record_avg_processor" (
  "avg_processor_processing_record_id" uuid not null,
  "avg_processor_id" uuid not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "avg_processor_processing_record_avg_processor" owner TO "dpr";

alter table
  "avg_processor_processing_record_avg_processor"
add
  constraint "avg_processor_processing_record_avg_processor_avg_processor_processing_record_id_foreign" foreign key (
    "avg_processor_processing_record_id"
  ) references "avg_processor_processing_records" ("id") on delete cascade;

alter table
  "avg_processor_processing_record_avg_processor"
add
  constraint "avg_processor_processing_record_avg_processor_avg_processor_id_foreign" foreign key ("avg_processor_id") references "avg_processors" ("id") on delete cascade;