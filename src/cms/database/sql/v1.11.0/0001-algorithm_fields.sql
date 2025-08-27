alter table
  "algorithm_records"
add
  column "resp_legal_base_title" varchar(255) null;

alter table
  "algorithm_records"
add
  column "oper_data_title" varchar(255) null;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2025_08_13_074149_algorithm_fields"',
    now(),
    now()
  );