create table "exports" (
  "id" bigserial not null primary key,
  "completed_at" timestamp(0) without time zone null,
  "file_disk" varchar(255) not null,
  "file_name" varchar(255) null,
  "exporter" varchar(255) not null,
  "processed_rows" integer not null default '0',
  "total_rows" integer not null,
  "successful_rows" integer not null default '0',
  "user_id" uuid not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "exports" owner TO "dpr";

alter table
  "exports"
add
  constraint "exports_user_id_foreign" foreign key ("user_id") references "users" ("id") on delete cascade;

alter table
  "avg_processor_processing_records"
drop
  column "geb_pia";

alter table
  "avg_responsible_processing_records"
drop
  column "outside_eu_protection_level";

alter table
  "avg_responsible_processing_records"
drop
  column "geb_dpia_executed";

alter table
  "avg_responsible_processing_records"
drop
  column "geb_dpia_automated";

alter table
  "avg_responsible_processing_records"
drop
  column "geb_dpia_large_scale_processing";

alter table
  "avg_responsible_processing_records"
drop
  column "geb_dpia_large_scale_monitoring";

alter table
  "avg_responsible_processing_records"
drop
  column "geb_dpia_list_required";

alter table
  "avg_responsible_processing_records"
drop
  column "geb_dpia_criteria_wp248";

alter table
  "avg_responsible_processing_records"
drop
  column "geb_dpia_high_risk_freedoms";

alter table
  "stakeholder_data_items"
drop
  column "is_source_stakeholder";

alter table
  "stakeholder_data_items"
drop
  column "is_stakeholder_mandatory";

alter table
  "avg_processor_processing_records"
add
  column "geb_pia" varchar(255) not null default 'unknown';

alter table
  "avg_responsible_processing_records"
add
  column "outside_eu_protection_level" varchar(255) not null default 'unknown';

alter table
  "avg_responsible_processing_records"
add
  column "geb_dpia_executed" varchar(255) not null default 'unknown';

alter table
  "avg_responsible_processing_records"
add
  column "geb_dpia_automated" varchar(255) not null default 'unknown';

alter table
  "avg_responsible_processing_records"
add
  column "geb_dpia_large_scale_processing" varchar(255) not null default 'unknown';

alter table
  "avg_responsible_processing_records"
add
  column "geb_dpia_large_scale_monitoring" varchar(255) not null default 'unknown';

alter table
  "avg_responsible_processing_records"
add
  column "geb_dpia_list_required" varchar(255) not null default 'unknown';

alter table
  "avg_responsible_processing_records"
add
  column "geb_dpia_criteria_wp248" varchar(255) not null default 'unknown';

alter table
  "avg_responsible_processing_records"
add
  column "geb_dpia_high_risk_freedoms" varchar(255) not null default 'unknown';

alter table
  "stakeholder_data_items"
add
  column "is_source_stakeholder" varchar(255) not null default 'unknown';

alter table
  "stakeholder_data_items"
add
  column "is_stakeholder_mandatory" varchar(255) not null default 'unknown';

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_02_28_141119_create_exports_table"',
    now(),
    now()
  );