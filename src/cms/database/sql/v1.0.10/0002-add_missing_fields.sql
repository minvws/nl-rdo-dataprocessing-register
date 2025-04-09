alter table
  "avg_responsible_processing_records"
add
  column "measures_description" text null;

create table "public_website_builds" (
  "published_at" timestamp(0) without time zone not null
);

ALTER TABLE
  "public_website_builds" owner TO "dpr";

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_03_07_085128_add_missing_fields"',
    now(),
    now()
  );