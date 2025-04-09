alter table
  "users"
drop
  constraint "users_email_unique";

CREATE UNIQUE INDEX users_email_unique ON users (email)
WHERE
  deleted_at IS NULL;

;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_03_14_105346_user_email_unique"',
    now(),
    now()
  );