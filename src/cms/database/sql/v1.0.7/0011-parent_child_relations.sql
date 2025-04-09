alter table
  "algorithm_records"
add
  column "parent_id" uuid null;

alter table
  "algorithm_records"
add
  constraint "algorithm_records_parent_id_foreign" foreign key ("parent_id") references "algorithm_records" ("id");

alter table
  "avg_processor_processing_records"
add
  column "parent_id" uuid null;

alter table
  "avg_processor_processing_records"
add
  constraint "avg_processor_processing_records_parent_id_foreign" foreign key ("parent_id") references "avg_processor_processing_records" ("id");

alter table
  "avg_responsible_processing_records"
add
  column "parent_id" uuid null;

alter table
  "avg_responsible_processing_records"
add
  constraint "avg_responsible_processing_records_parent_id_foreign" foreign key ("parent_id") references "avg_responsible_processing_records" ("id");

alter table
  "wpg_processing_records"
add
  column "parent_id" uuid null;

alter table
  "wpg_processing_records"
add
  constraint "wpg_processing_records_parent_id_foreign" foreign key ("parent_id") references "wpg_processing_records" ("id");

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_02_01_095618_parent_child_relations"',
    now(),
    now()
  );