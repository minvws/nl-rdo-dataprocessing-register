alter table
  "wpg_processing_records"
drop
  column "contact_person_id";

drop
  table "avg_processor_processing_record_contact_person";

drop
  table "avg_responsible_processing_record_contact_person";

drop
  table "contact_person_wpg_processing_record";

create table "contact_person_relatables" (
  "contact_person_id" uuid not null,
  "contact_person_relatable_type" varchar(255) not null,
  "contact_person_relatable_id" uuid not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "contact_person_relatables" owner TO "dpr";

alter table
  "contact_person_relatables"
add
  constraint "conta_perso_relat_contact_persons_id_foreign" foreign key ("contact_person_id") references "contact_persons" ("id") on delete cascade;

create index "conta_perso_relat_con_per_rel_typ_con_per_rel_id_index" on "contact_person_relatables" (
  "contact_person_relatable_type",
  "contact_person_relatable_id"
);