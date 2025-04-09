alter table
  "users"
drop
  column "email_verified_at";

alter table
  "users"
add
  column "otp_secret" text null;

alter table
  "users"
add
  column "otp_recovery_codes" text null;

alter table
  "users"
add
  column "otp_confirmed_at" timestamp(0) without time zone null;

alter table
  "users"
add
  column "otp_validated_at" timestamp(0) without time zone null;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_02_21_074928_two_factor_authentication"',
    now(),
    now()
  );