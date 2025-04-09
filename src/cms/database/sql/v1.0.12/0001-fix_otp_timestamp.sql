alter table
  "users"
drop
  column "otp_validated_at";

alter table
  "users"
add
  column "otp_timestamp" integer not null default '0';

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_03_21_104731_fix_otp_timestamp"',
    now(),
    now()
  );