create table "public_website" (
  "id" uuid not null,
  "home_content" text null,
  "organisation_content" text null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "public_website" owner TO "dpr";

alter table
  "public_website"
add
  primary key ("id");

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_03_14_153813_create_public_website"',
    now(),
    now()
  );