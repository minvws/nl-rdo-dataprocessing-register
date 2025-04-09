ALTER TABLE
  stakeholder_data_items ALTER COLUMN is_stakeholder_mandatory
DROP
  DEFAULT;

ALTER TABLE
  stakeholder_data_items ALTER is_stakeholder_mandatory TYPE bool USING CASE WHEN is_stakeholder_mandatory = 'yes' THEN
    TRUE
  ELSE
    FALSE
  END;

ALTER TABLE
  stakeholder_data_items ALTER COLUMN is_stakeholder_mandatory
SET
  DEFAULT false;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_11_01_164717_fix_yesnounknown_to_boolean"',
    now(),
    now()
  );