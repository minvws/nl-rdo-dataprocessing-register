alter table
  "avg_goals"
drop
  column "avg_goal_legal_base_id";

alter table
  "avg_goals"
add
  column "avg_goal_legal_base" varchar(255) null;

alter table
  "stakeholders"
add
  column "special_collected_data_explanation" text null;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_04_29_144410_convert_avg_goal_legal_base_to_options"',
    now(),
    now()
  );