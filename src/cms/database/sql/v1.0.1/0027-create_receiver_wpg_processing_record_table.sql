create table "receiver_wpg_processing_record" (
  "receiver_id" uuid not null,
  "wpg_processing_record_id" uuid not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "receiver_wpg_processing_record" owner TO "dpr";

alter table
  "receiver_wpg_processing_record"
add
  constraint "receiver_wpg_processing_record_receiver_id_foreign" foreign key ("receiver_id") references "receivers" ("id") on delete cascade;

alter table
  "receiver_wpg_processing_record"
add
  constraint "receiver_wpg_processing_record_wpg_processing_record_id_foreign" foreign key ("wpg_processing_record_id") references "wpg_processing_records" ("id") on delete cascade;