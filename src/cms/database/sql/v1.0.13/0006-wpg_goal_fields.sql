ALTER TABLE
  wpg_goals ALTER description TYPE TEXT;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_03_22_131708_wpg_goal_fields"',
    now(),
    now()
  );