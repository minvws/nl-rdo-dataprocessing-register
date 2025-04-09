create table "imports" (
  "id" bigserial not null primary key,
  "completed_at" timestamp(0) without time zone null,
  "file_name" varchar(255) not null,
  "file_path" varchar(512) not null,
  "importer" varchar(255) not null,
  "processed_rows" integer not null default '0',
  "total_rows" integer not null,
  "successful_rows" integer not null default '0',
  "user_id" uuid not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "imports" owner TO "dpr";

alter table
  "imports"
add
  constraint "imports_user_id_foreign" foreign key ("user_id") references "users" ("id") on delete cascade;

create table "failed_import_rows" (
  "id" bigserial not null primary key,
  "data" json not null,
  "import_id" bigint not null,
  "validation_error" text null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "failed_import_rows" owner TO "dpr";

alter table
  "failed_import_rows"
add
  constraint "failed_import_rows_import_id_foreign" foreign key ("import_id") references "imports" ("id") on delete cascade;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_03_14_105554_create_imports_table"',
    now(),
    now()
  );