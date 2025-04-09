alter table
  "stakeholders"
add
  column "citizen_service_numbers" boolean not null default '0';

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_12_10_091458_stakeholder_sensitive_data"',
    now(),
    now()
  );