update
  "users"
set
  "otp_secret" = null,
  "otp_recovery_codes" = null,
  "otp_confirmed_at" = null;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_05_16_081805_remove_user_otp_timestamp"',
    now(),
    now()
  );