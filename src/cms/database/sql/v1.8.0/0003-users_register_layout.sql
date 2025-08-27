alter table
  "users"
add
  column "register_layout" varchar(255) not null default 'steps';

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2025_06_12_133905_users_register_layout"',
    now(),
    now()
  );