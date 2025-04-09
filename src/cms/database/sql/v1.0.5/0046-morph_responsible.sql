alter table
  "wpg_processing_records"
drop
  column "responsible_id";

drop
  table "avg_processor_processing_record_responsible";

drop
  table "avg_responsible_processing_record_responsible";

drop
  table "responsible_wpg_processing_record";

create table "responsible_relatables" (
  "responsible_id" uuid not null,
  "responsible_relatable_type" varchar(255) not null,
  "responsible_relatable_id" uuid not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "responsible_relatables" owner TO "dpr";

alter table
  "responsible_relatables"
add
  constraint "responsi_relatabl_responsibles_id_foreign" foreign key ("responsible_id") references "responsibles" ("id") on delete cascade;

create index "responsi_relatabl_respo_relat_type_respo_relat_id_index" on "responsible_relatables" (
  "responsible_relatable_type", "responsible_relatable_id"
);