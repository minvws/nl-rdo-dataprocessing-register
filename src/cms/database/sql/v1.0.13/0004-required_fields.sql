ALTER TABLE
  remarks ALTER body
DROP
  NOT NULL;

UPDATE
  avg_processor_processing_records
SET
  review_at = '2025-01-01 00:00:00'
WHERE
  review_at IS NULL;

UPDATE
  avg_responsible_processing_records
SET
  review_at = '2025-01-01 00:00:00'
WHERE
  review_at IS NULL;

UPDATE
  wpg_processing_records
SET
  review_at = '2025-01-01 00:00:00'
WHERE
  review_at IS NULL;

ALTER TABLE
  avg_processor_processing_records ALTER review_at
DROP
  NOT NULL;

ALTER TABLE
  avg_responsible_processing_records ALTER review_at
DROP
  NOT NULL;

ALTER TABLE
  wpg_processing_records ALTER review_at
DROP
  NOT NULL;

alter table
  "organisations"
add
  column "review_at_default_in_months" integer not null default '0';

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_03_22_092750_required_fields"',
    now(),
    now()
  );