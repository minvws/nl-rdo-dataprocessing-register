create table "avg_processor_processing_record_contact_person" (
  "avg_processor_processing_record_id" uuid not null,
  "contact_person_id" uuid not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "avg_processor_processing_record_contact_person" owner TO "dpr";

alter table
  "avg_processor_processing_record_contact_person"
add
  constraint "avg_processor_processing_record_contact_person_avg_processor_processing_record_id_foreign" foreign key (
    "avg_processor_processing_record_id"
  ) references "avg_processor_processing_records" ("id") on delete cascade;

alter table
  "avg_processor_processing_record_contact_person"
add
  constraint "avg_processor_processing_record_contact_person_contact_person_id_foreign" foreign key ("contact_person_id") references "contact_persons" ("id") on delete cascade;