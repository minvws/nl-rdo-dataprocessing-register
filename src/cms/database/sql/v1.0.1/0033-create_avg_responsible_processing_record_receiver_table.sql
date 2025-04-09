create table "avg_responsible_processing_record_receiver" (
  "avg_responsible_processing_record_id" uuid not null,
  "receiver_id" uuid not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "avg_responsible_processing_record_receiver" owner TO "dpr";

alter table
  "avg_responsible_processing_record_receiver"
add
  constraint "avg_responsible_processing_record_receiver_avg_responsible_processing_record_id_foreign" foreign key (
    "avg_responsible_processing_record_id"
  ) references "avg_responsible_processing_records" ("id") on delete cascade;

alter table
  "avg_responsible_processing_record_receiver"
add
  constraint "avg_responsible_processing_record_receiver_receiver_id_foreign" foreign key ("receiver_id") references "receivers" ("id") on delete cascade;