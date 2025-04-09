alter table
  "processors"
drop
  column "processor_type_id";

alter table
  "responsibles"
drop
  column "responsible_type_id";

drop
  table if exists "processor_types";

drop
  table if exists "responsible_types";

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_05_22_130515_remove_reponsible_type"',
    now(),
    now()
  );