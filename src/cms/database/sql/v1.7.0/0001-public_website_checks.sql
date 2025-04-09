drop
  table "public_website_builds";

create table "public_website_checks" (
  "id" uuid not null,
  "build_date" timestamp(0) without time zone not null,
  "content" json not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "public_website_checks" owner TO "dpr";

alter table
  "public_website_checks"
add
  primary key ("id");

create table "public_website_snapshot_entries" (
  "id" uuid not null,
  "last_public_website_check_id" uuid not null,
  "snapshot_id" uuid not null,
  "url" varchar(255) not null,
  "start_date" timestamp(0) without time zone not null,
  "end_date" timestamp(0) without time zone null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "public_website_snapshot_entries" owner TO "dpr";

alter table
  "public_website_snapshot_entries"
add
  primary key ("id");

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2025_01_23_125701_public_website_checks"',
    now(),
    now()
  );