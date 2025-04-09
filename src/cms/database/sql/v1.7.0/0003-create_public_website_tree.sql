create table "public_website_tree" (
  "id" uuid not null,
  "organisation_id" uuid null,
  "parent_id" uuid null,
  "order" integer not null default '0',
  "title" varchar(255) not null,
  "slug" varchar(255) null,
  "public_from" date null,
  "public_website_content" text null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "public_website_tree" owner TO "dpr";

alter table
  "public_website_tree"
add
  constraint "public_website_tree_organisation_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "public_website_tree"
add
  primary key ("id");

create index "public_website_tree_order_index" on "public_website_tree" ("order");

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2025_02_17_152708_create_public_website_tree"',
    now(),
    now()
  );