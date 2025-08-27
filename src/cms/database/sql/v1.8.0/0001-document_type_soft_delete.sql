alter table
  "document_types"
add
  column "deleted_at" timestamp(0) without time zone null;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2025_04_16_115925_document_type_soft_delete"',
    now(),
    now()
  );