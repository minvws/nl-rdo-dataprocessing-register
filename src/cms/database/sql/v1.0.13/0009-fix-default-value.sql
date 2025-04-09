ALTER TABLE
  avg_processor_processing_records ALTER COLUMN has_arrangements_with_responsibles
SET
  DEFAULT 'unknown';

;

ALTER TABLE
  avg_processor_processing_records ALTER COLUMN has_arrangements_with_processors
SET
  DEFAULT 'unknown';

;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_03_25_140650_fix-default-value"',
    now(),
    now()
  );