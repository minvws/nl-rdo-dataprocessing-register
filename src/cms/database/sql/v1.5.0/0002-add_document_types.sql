create table "document_types" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "name" text not null,
  "enabled" boolean not null default '1',
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "document_types" owner TO "dpr";

alter table
  "document_types"
add
  constraint "document_types_organisations_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "document_types"
add
  primary key ("id");

create index "document_types_name_index" on "document_types" ("name");

alter table
  "documents"
add
  column "document_type_id" uuid null;

alter table
  "documents"
add
  constraint "documents_document_types_id_foreign" foreign key ("document_type_id") references "document_types" ("id");

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_10_30_131350_add_document_types"',
    now(),
    now()
  );