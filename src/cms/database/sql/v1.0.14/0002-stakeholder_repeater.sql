alter table
  "stakeholders"
add
  column "sort" integer not null default '0';

alter table
  "stakeholders"
drop
  column "no_special_collected_data";

alter table
  "stakeholder_data_items"
add
  column "sort" integer not null default '0';

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_03_29_100021_stakeholder_repeater"',
    now(),
    now()
  );