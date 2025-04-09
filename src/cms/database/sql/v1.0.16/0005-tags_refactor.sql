ALTER TABLE
  tags ALTER COLUMN name
SET
  DATA TYPE text;

ALTER TABLE
  tags
DROP
  COLUMN slug;

ALTER TABLE
  tags
DROP
  COLUMN order_column;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_04_08_123453_tags_refactor"',
    now(),
    now()
  );