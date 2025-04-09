alter table
  "algorithm_records"
drop
  column "number";

alter table
  "avg_processor_processing_records"
drop
  column "number";

alter table
  "avg_responsible_processing_records"
drop
  column "number";

alter table
  "wpg_processing_records"
drop
  column "number";

ALTER TABLE
  organisations ALTER COLUMN databreach_entity_number_counter_id
SET
  NOT NULL;

;

ALTER TABLE
  organisations ALTER COLUMN register_entity_number_counter_id
SET
  NOT NULL;

;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_04_30_121615_finsh_entity_number_conversion"',
    now(),
    now()
  );