alter table
  "algorithm_records"
add
  column "public_page_link" varchar(255) null;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_04_10_082058_alter_algorithm_records_table"',
    now(),
    now()
  );