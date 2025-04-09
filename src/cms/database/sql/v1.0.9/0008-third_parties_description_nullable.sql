ALTER TABLE
  avg_processor_processing_records ALTER third_parties_description
DROP
  DEFAULT;

ALTER TABLE
  avg_processor_processing_records ALTER third_parties_description
DROP
  NOT NULL;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_03_05_111444_third_parties_description_nullable"',
    now(),
    now()
  );