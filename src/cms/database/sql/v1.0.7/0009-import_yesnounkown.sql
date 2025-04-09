drop
  table "avg_processor_processing_record_avg_processor";

drop
  table "avg_processors";

alter table
  "avg_responsible_processing_records"
drop
  column "outside_eu_protection_level";

alter table
  "avg_responsible_processing_records"
drop
  column "dpia";

alter table
  "avg_responsible_processing_records"
add
  column "outside_eu_protection_level" varchar(255) not null default 'onbekend';

alter table
  "avg_responsible_processing_records"
add
  column "geb_dpia_executed" varchar(255) not null default 'onbekend';

alter table
  "avg_responsible_processing_records"
add
  column "geb_dpia_automated" varchar(255) not null default 'onbekend';

alter table
  "avg_responsible_processing_records"
add
  column "geb_dpia_large_scale_processing" varchar(255) not null default 'onbekend';

alter table
  "avg_responsible_processing_records"
add
  column "geb_dpia_large_scale_monitoring" varchar(255) not null default 'onbekend';

alter table
  "avg_responsible_processing_records"
add
  column "geb_dpia_list_required" varchar(255) not null default 'onbekend';

alter table
  "avg_responsible_processing_records"
add
  column "geb_dpia_criteria_wp248" varchar(255) not null default 'onbekend';

alter table
  "avg_responsible_processing_records"
add
  column "geb_dpia_high_risk_freedoms" varchar(255) not null default 'onbekend';

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_01_30_130618_import_yesnounkown"',
    now(),
    now()
  );