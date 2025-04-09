create table "processor_wpg_processing_record" (
  "processor_id" uuid not null,
  "wpg_processing_record_id" uuid not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "processor_wpg_processing_record" owner TO "dpr";

alter table
  "processor_wpg_processing_record"
add
  constraint "processor_wpg_processing_record_processor_id_foreign" foreign key ("processor_id") references "processors" ("id") on delete cascade;

alter table
  "processor_wpg_processing_record"
add
  constraint "processor_wpg_processing_record_wpg_processing_record_id_foreign" foreign key ("wpg_processing_record_id") references "wpg_processing_records" ("id") on delete cascade;