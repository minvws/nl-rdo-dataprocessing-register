alter table
  "users"
drop
  column "otp_recovery_codes";

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2025_01_27_152744_drop_recovery_codes"',
    now(),
    now()
  );