ALTER TABLE
  avg_processor_processing_records ALTER has_arrangements_with_responsibles TYPE VARCHAR(255);

ALTER TABLE
  avg_processor_processing_records ALTER has_arrangements_with_processors TYPE VARCHAR(255);

UPDATE
  avg_processor_processing_records
SET
  has_arrangements_with_processors = 'no'
WHERE
  has_arrangements_with_processors = 'false';

UPDATE
  avg_processor_processing_records
SET
  has_arrangements_with_processors = 'yes'
WHERE
  has_arrangements_with_processors = 'true';

UPDATE
  avg_processor_processing_records
SET
  has_arrangements_with_responsibles = 'no'
WHERE
  has_arrangements_with_responsibles = 'false';

UPDATE
  avg_processor_processing_records
SET
  has_arrangements_with_responsibles = 'yes'
WHERE
  has_arrangements_with_responsibles = 'true';

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_03_12_142702_avg_processor_processing_record_alingment"',
    now(),
    now()
  );