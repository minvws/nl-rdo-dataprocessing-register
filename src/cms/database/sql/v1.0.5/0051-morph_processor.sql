drop
  table "avg_responsible_processing_record_processor";

drop
  table "processor_wpg_processing_record";

create table "processor_relatables" (
  "processor_id" uuid not null,
  "processor_relatable_type" varchar(255) not null,
  "processor_relatable_id" uuid not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "processor_relatables" owner TO "dpr";

alter table
  "processor_relatables"
add
  constraint "processo_relatabl_processors_id_foreign" foreign key ("processor_id") references "processors" ("id") on delete cascade;

create index "processo_relatabl_proce_relat_type_proce_relat_id_index" on "processor_relatables" (
  "processor_relatable_type", "processor_relatable_id"
);