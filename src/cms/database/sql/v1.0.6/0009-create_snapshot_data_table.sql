create table "snapshot_data" (
  "id" uuid not null,
  "snapshot_id" uuid not null,
  "public_frontmatter" text null,
  "public_markdown" text null,
  "private_frontmatter" text not null,
  "private_markdown" text not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "snapshot_data" owner TO "dpr";

alter table
  "snapshot_data"
add
  constraint "snapshot_data_snapshot_id_foreign" foreign key ("snapshot_id") references "snapshots" ("id");

alter table
  "snapshot_data"
add
  constraint "snapshot_data_snapshot_id_unique" unique ("snapshot_id");

alter table
  "snapshot_data"
add
  primary key ("id");