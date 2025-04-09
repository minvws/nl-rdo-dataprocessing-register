update
  "users"
set
  "otp_secret" = null,
  "otp_recovery_codes" = null,
  "otp_confirmed_at" = null,
  "otp_timestamp" = 0;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_04_30_085324_delete_otp_secrets"',
    now(),
    now()
  );