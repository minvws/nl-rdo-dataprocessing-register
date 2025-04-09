create table "categorizables" (
  "category_id" uuid not null,
  "categorizable_type" varchar(255) not null,
  "categorizable_id" uuid not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "categorizables" owner TO "dpr";

alter table
  "categorizables"
add
  constraint "categorizables_category_id_foreign" foreign key ("category_id") references "categories" ("id") on delete cascade;

create index "categorizables_categorizable_type_categorizable_id_index" on "categorizables" (
  "categorizable_type", "categorizable_id"
);