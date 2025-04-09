create table "admin_log_entries" (
  "id" bigserial not null primary key,
  "message" text not null default '',
  "context" json not null default '[]',
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "admin_log_entries" owner TO "dpr";

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_01_11_130601_create_admin_log_entries_table"',
    now(),
    now()
  );