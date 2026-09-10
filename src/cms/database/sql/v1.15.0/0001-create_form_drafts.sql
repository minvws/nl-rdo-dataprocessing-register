create table "form_drafts" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "user_id" uuid not null,
  "resource_class" varchar(255) not null,
  "record_id" uuid null,
  "draft_key" uuid not null,
  "payload" text not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "form_drafts" owner TO "dpr";

alter table
  "form_drafts"
add
  constraint "form_drafts_organisation_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "form_drafts"
add
  constraint "form_drafts_user_id_foreign" foreign key ("user_id") references "users" ("id") on delete cascade;

alter table
  "form_drafts"
add
  constraint "form_drafts_user_id_resource_class_draft_key_unique" unique (
    "user_id", "resource_class", "draft_key"
  );

alter table
  "form_drafts"
add
  primary key ("id");

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2026_08_21_080000_create_form_drafts"',
    now(),
    now()
  );