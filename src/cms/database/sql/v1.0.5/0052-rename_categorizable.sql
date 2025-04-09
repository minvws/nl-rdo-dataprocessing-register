drop
  table if exists "categorizables";

create table "category_relatables" (
  "category_id" uuid not null,
  "category_relatable_type" varchar(255) not null,
  "category_relatable_id" uuid not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "category_relatables" owner TO "dpr";

alter table
  "category_relatables"
add
  constraint "category_relatabl_categories_id_foreign" foreign key ("category_id") references "categories" ("id") on delete cascade;

create index "category_relatabl_categ_relat_type_categ_relat_id_index" on "category_relatables" (
  "category_relatable_type", "category_relatable_id"
);