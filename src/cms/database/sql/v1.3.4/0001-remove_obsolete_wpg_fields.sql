alter table
  "wpg_processing_records"
drop
  column "no_provisioning";

alter table
  "wpg_processing_records"
drop
  column "no_transfer";

alter table
  "wpg_processing_records"
drop
  column "police_none";

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_05_31_081445_remove_obsolete_wpg_fields"',
    now(),
    now()
  );