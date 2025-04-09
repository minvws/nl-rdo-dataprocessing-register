create table "avg_processor_processing_record_service" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "name" text not null,
  "enabled" boolean not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "avg_processor_processing_record_service" owner TO "dpr";

alter table
  "avg_processor_processing_record_service"
add
  constraint "av_pr_pr_re_se_organisations_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "avg_processor_processing_record_service"
add
  primary key ("id");

create index "avg_processor_processing_record_service_name_index" on "avg_processor_processing_record_service" ("name");

alter table
  "avg_processor_processing_records"
drop
  column "service";

alter table
  "avg_processor_processing_records"
add
  column "avg_processor_processing_record_service_id" uuid null;

alter table
  "avg_processor_processing_records"
add
  constraint "avg_pro_pro_rec_av_pr_pr_re_se_id_foreign" foreign key (
    "avg_processor_processing_record_service_id"
  ) references "avg_processor_processing_record_service" ("id");