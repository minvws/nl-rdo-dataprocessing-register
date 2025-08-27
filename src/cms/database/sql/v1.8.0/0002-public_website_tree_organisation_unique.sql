UPDATE
  public_website_tree
SET
  slug = id
WHERE
  slug IS NULL;

alter table
  "public_website_tree"
add
  constraint "public_website_tree_organisation_id_unique" unique ("organisation_id");

alter table
  "public_website_tree" alter column "slug" type varchar(255),
  alter column "slug"
set
  not null,
  alter column "slug"
drop
  default,
  alter column "slug"
drop
  identity if exists;

alter table
  "public_website_tree"
add
  constraint "public_website_tree_slug_unique" unique ("slug");

comment on column "public_website_tree"."slug" is NULL;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2025_04_24_142759_public_website_tree_organisation_unique"',
    now(),
    now()
  );