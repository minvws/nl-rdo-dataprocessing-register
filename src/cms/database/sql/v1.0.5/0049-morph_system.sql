drop
  table "avg_processor_processing_record_system";

drop
  table "avg_responsible_processing_record_system";

drop
  table "system_wpg_processing_record";

create table "system_relatables" (
  "system_id" uuid not null,
  "system_relatable_type" varchar(255) not null,
  "system_relatable_id" uuid not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "system_relatables" owner TO "dpr";

alter table
  "system_relatables"
add
  constraint "system_relatabl_systems_id_foreign" foreign key ("system_id") references "systems" ("id") on delete cascade;

create index "system_relatabl_syste_relat_type_syste_relat_id_foreign" on "system_relatables" (
  "system_relatable_type", "system_relatable_id"
);