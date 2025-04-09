drop
  table "avg_responsible_processing_record_receiver";

drop
  table "receiver_wpg_processing_record";

create table "receiver_relatables" (
  "receiver_id" uuid not null,
  "receiver_relatable_type" varchar(255) not null,
  "receiver_relatable_id" uuid not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "receiver_relatables" owner TO "dpr";

alter table
  "receiver_relatables"
add
  constraint "receiver_relatabl_receivers_id_foreign" foreign key ("receiver_id") references "receivers" ("id") on delete cascade;

create index "receiver_relatabl_recei_relat_type_recei_relat_id_index" on "receiver_relatables" (
  "receiver_relatable_type", "receiver_relatable_id"
);