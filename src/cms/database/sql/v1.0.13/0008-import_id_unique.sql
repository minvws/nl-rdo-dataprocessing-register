UPDATE
  algorithm_records
SET
  import_id = NULL;

alter table
  "algorithm_records"
add
  constraint "algorith_records_organisa_id_import_id_unique" unique ("organisation_id", "import_id");

UPDATE
  stakeholders
SET
  import_id = NULL;

alter table
  "stakeholders"
add
  constraint "stakeholders_organisa_id_import_id_unique" unique ("organisation_id", "import_id");

UPDATE
  stakeholder_data_items
SET
  import_id = NULL;

alter table
  "stakeholder_data_items"
add
  constraint "stake_data_items_organisa_id_import_id_unique" unique ("organisation_id", "import_id");

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_03_25_085322_import_id_unique"',
    now(),
    now()
  );