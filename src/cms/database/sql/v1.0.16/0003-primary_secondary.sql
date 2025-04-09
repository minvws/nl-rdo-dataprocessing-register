alter table
  "avg_processor_processing_records"
add
  column "data_collection_source" varchar(255) not null default 'primary';

alter table
  "avg_responsible_processing_records"
add
  column "data_collection_source" varchar(255) not null default 'primary';

alter table
  "wpg_processing_records"
add
  column "data_collection_source" varchar(255) not null default 'primary';

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_04_05_140933_primary_secondary"',
    now(),
    now()
  );