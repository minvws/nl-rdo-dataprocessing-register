create table "documents" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "name" varchar(255) not null,
  "expires_at" date null,
  "notify_at" date null,
  "location" text null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null,
  "deleted_at" timestamp(0) without time zone null
);

ALTER TABLE
  "documents" owner TO "dpr";

alter table
  "documents"
add
  constraint "documents_organisation_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "documents"
add
  primary key ("id");

create table "document_relatables" (
  "document_id" uuid not null,
  "document_relatable_type" varchar(255) not null,
  "document_relatable_id" uuid not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "document_relatables" owner TO "dpr";

alter table
  "document_relatables"
add
  constraint "document_relatabl_documents_id_foreign" foreign key ("document_id") references "documents" ("id") on delete cascade;

create index "document_relatabl_docum_relat_type_docum_relat_id_index" on "document_relatables" (
  "document_relatable_type", "document_relatable_id"
);

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_04_17_130312_create_documents"',
    now(),
    now()
  );