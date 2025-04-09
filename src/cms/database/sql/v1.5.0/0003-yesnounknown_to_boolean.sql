ALTER TABLE
  stakeholder_data_items ALTER COLUMN is_source_stakeholder
DROP
  DEFAULT;

ALTER TABLE
  stakeholder_data_items ALTER is_source_stakeholder TYPE bool USING CASE WHEN is_source_stakeholder = 'yes' THEN
    TRUE
  ELSE
    FALSE
  END;

ALTER TABLE
  stakeholder_data_items ALTER COLUMN is_source_stakeholder
SET
  DEFAULT false;

ALTER TABLE
  avg_processor_processing_records ALTER COLUMN geb_pia
DROP
  DEFAULT;

ALTER TABLE
  avg_processor_processing_records ALTER geb_pia TYPE bool USING CASE WHEN geb_pia = 'yes' THEN
    TRUE
  ELSE
    FALSE
  END;

ALTER TABLE
  avg_processor_processing_records ALTER COLUMN geb_pia
SET
  DEFAULT false;

ALTER TABLE
  avg_processor_processing_records ALTER COLUMN outside_eu_protection_level
DROP
  DEFAULT;

ALTER TABLE
  avg_processor_processing_records ALTER outside_eu_protection_level TYPE bool USING CASE WHEN outside_eu_protection_level = 'yes' THEN
    TRUE
  ELSE
    FALSE
  END;

ALTER TABLE
  avg_processor_processing_records ALTER COLUMN outside_eu_protection_level
SET
  DEFAULT false;

ALTER TABLE
  avg_responsible_processing_records ALTER COLUMN geb_dpia_executed
DROP
  DEFAULT;

ALTER TABLE
  avg_responsible_processing_records ALTER geb_dpia_executed TYPE bool USING CASE WHEN geb_dpia_executed = 'yes' THEN
    TRUE
  ELSE
    FALSE
  END;

ALTER TABLE
  avg_responsible_processing_records ALTER COLUMN geb_dpia_executed
SET
  DEFAULT false;

ALTER TABLE
  avg_responsible_processing_records ALTER COLUMN geb_dpia_automated
DROP
  DEFAULT;

ALTER TABLE
  avg_responsible_processing_records ALTER geb_dpia_automated TYPE bool USING CASE WHEN geb_dpia_automated = 'yes' THEN
    TRUE
  ELSE
    FALSE
  END;

ALTER TABLE
  avg_responsible_processing_records ALTER COLUMN geb_dpia_automated
SET
  DEFAULT false;

ALTER TABLE
  avg_responsible_processing_records ALTER COLUMN geb_dpia_large_scale_processing
DROP
  DEFAULT;

ALTER TABLE
  avg_responsible_processing_records ALTER geb_dpia_large_scale_processing TYPE bool USING CASE WHEN geb_dpia_large_scale_processing = 'yes' THEN
    TRUE
  ELSE
    FALSE
  END;

ALTER TABLE
  avg_responsible_processing_records ALTER COLUMN geb_dpia_large_scale_processing
SET
  DEFAULT false;

ALTER TABLE
  avg_responsible_processing_records ALTER COLUMN geb_dpia_large_scale_monitoring
DROP
  DEFAULT;

ALTER TABLE
  avg_responsible_processing_records ALTER geb_dpia_large_scale_monitoring TYPE bool USING CASE WHEN geb_dpia_large_scale_monitoring = 'yes' THEN
    TRUE
  ELSE
    FALSE
  END;

ALTER TABLE
  avg_responsible_processing_records ALTER COLUMN geb_dpia_large_scale_monitoring
SET
  DEFAULT false;

ALTER TABLE
  avg_responsible_processing_records ALTER COLUMN geb_dpia_list_required
DROP
  DEFAULT;

ALTER TABLE
  avg_responsible_processing_records ALTER geb_dpia_list_required TYPE bool USING CASE WHEN geb_dpia_list_required = 'yes' THEN
    TRUE
  ELSE
    FALSE
  END;

ALTER TABLE
  avg_responsible_processing_records ALTER COLUMN geb_dpia_list_required
SET
  DEFAULT false;

ALTER TABLE
  avg_responsible_processing_records ALTER COLUMN geb_dpia_criteria_wp248
DROP
  DEFAULT;

ALTER TABLE
  avg_responsible_processing_records ALTER geb_dpia_criteria_wp248 TYPE bool USING CASE WHEN geb_dpia_criteria_wp248 = 'yes' THEN
    TRUE
  ELSE
    FALSE
  END;

ALTER TABLE
  avg_responsible_processing_records ALTER COLUMN geb_dpia_criteria_wp248
SET
  DEFAULT false;

ALTER TABLE
  avg_responsible_processing_records ALTER COLUMN geb_dpia_high_risk_freedoms
DROP
  DEFAULT;

ALTER TABLE
  avg_responsible_processing_records ALTER geb_dpia_high_risk_freedoms TYPE bool USING CASE WHEN geb_dpia_high_risk_freedoms = 'yes' THEN
    TRUE
  ELSE
    FALSE
  END;

ALTER TABLE
  avg_responsible_processing_records ALTER COLUMN geb_dpia_high_risk_freedoms
SET
  DEFAULT false;

ALTER TABLE
  avg_responsible_processing_records ALTER COLUMN outside_eu_protection_level
DROP
  DEFAULT;

ALTER TABLE
  avg_responsible_processing_records ALTER outside_eu_protection_level TYPE bool USING CASE WHEN outside_eu_protection_level = 'yes' THEN
    TRUE
  ELSE
    FALSE
  END;

ALTER TABLE
  avg_responsible_processing_records ALTER COLUMN outside_eu_protection_level
SET
  DEFAULT false;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_10_30_152315_yesnounknown_to_boolean"',
    now(),
    now()
  );