alter table
  "users"
drop
  column "password";

create table "user_login_tokens" (
  "token" uuid not null,
  "user_id" uuid not null,
  "expires_at" timestamp(0) without time zone not null
);

ALTER TABLE
  "user_login_tokens" owner TO "dpr";

alter table
  "user_login_tokens"
add
  constraint "user_login_tokens_user_id_foreign" foreign key ("user_id") references "users" ("id") on delete cascade;

alter table
  "user_login_tokens"
add
  primary key ("token");

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_01_22_083936_passwordless_login_changes"',
    now(),
    now()
  );