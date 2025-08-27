alter table
  "users"
add
  column "mandateholder_notify_batch" varchar(255) not null default 'none';

alter table
  "users"
add
  column "mandateholder_notify_directly" varchar(255) not null default 'single';

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2025_06_25_103213_users_mandateholder_settings"',
    now(),
    now()
  );