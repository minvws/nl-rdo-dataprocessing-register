create table "tags" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "name" json not null,
  "slug" json not null,
  "type" varchar(255) null,
  "order_column" integer null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "tags" owner TO "dpr";

alter table
  "tags"
add
  constraint "tags_organisation_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "tags"
add
  primary key ("id");

create table "taggables" (
  "tag_id" uuid not null,
  "taggable_type" varchar(255) not null,
  "taggable_id" uuid not null
);

ALTER TABLE
  "taggables" owner TO "dpr";

alter table
  "taggables"
add
  constraint "taggables_tag_id_foreign" foreign key ("tag_id") references "tags" ("id") on delete cascade;

create index "taggables_taggable_type_taggable_id_index" on "taggables" ("taggable_type", "taggable_id");

alter table
  "taggables"
add
  constraint "taggables_tag_id_taggable_id_taggable_type_unique" unique (
    "tag_id", "taggable_id", "taggable_type"
  );