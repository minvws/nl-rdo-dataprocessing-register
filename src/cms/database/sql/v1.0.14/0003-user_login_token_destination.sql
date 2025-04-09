alter table
  "user_login_tokens"
add
  column "destination" varchar(255) null;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_03_29_103649_user_login_token_destination"',
    now(),
    now()
  );