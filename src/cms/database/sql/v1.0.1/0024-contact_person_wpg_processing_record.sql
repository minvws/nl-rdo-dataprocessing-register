create table "contact_person_wpg_processing_record" (
  "contact_person_id" uuid not null,
  "wpg_processing_record_id" uuid not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "contact_person_wpg_processing_record" owner TO "dpr";

alter table
  "contact_person_wpg_processing_record"
add
  constraint "contact_person_wpg_processing_record_contact_person_id_foreign" foreign key ("contact_person_id") references "contact_persons" ("id") on delete cascade;

alter table
  "contact_person_wpg_processing_record"
add
  constraint "contact_person_wpg_processing_record_wpg_processing_record_id_foreign" foreign key ("wpg_processing_record_id") references "wpg_processing_records" ("id") on delete cascade;