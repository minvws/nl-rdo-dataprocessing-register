create table "responsible_wpg_processing_record" (
  "responsible_id" uuid not null,
  "wpg_processing_record_id" uuid not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "responsible_wpg_processing_record" owner TO "dpr";

alter table
  "responsible_wpg_processing_record"
add
  constraint "responsible_wpg_processing_record_responsible_id_foreign" foreign key ("responsible_id") references "responsibles" ("id") on delete cascade;

alter table
  "responsible_wpg_processing_record"
add
  constraint "responsible_wpg_processing_record_wpg_processing_record_id_foreign" foreign key ("wpg_processing_record_id") references "wpg_processing_records" ("id") on delete cascade;