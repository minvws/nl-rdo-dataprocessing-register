alter table
  "avg_goals"
add
  column "remarks" text null;

alter table
  "avg_goals"
add
  column "sort" integer not null default '0';

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_03_29_092946_avggoal_improvements"',
    now(),
    now()
  );