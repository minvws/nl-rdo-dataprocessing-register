create table "related_snapshot_sources" (
  "id" uuid not null,
  "snapshot_id" uuid not null,
  "snapshot_source_type" varchar(255) not null,
  "snapshot_source_id" uuid not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "related_snapshot_sources" owner TO "dpr";

alter table
  "related_snapshot_sources"
add
  constraint "related_snapshot_sources_snapshot_id_foreign" foreign key ("snapshot_id") references "snapshots" ("id") on delete cascade;

create index "related_snapshot_sources_snapshot_source_type_snapshot_source_id_index" on "related_snapshot_sources" (
  "snapshot_source_type", "snapshot_source_id"
);

alter table
  "related_snapshot_sources"
add
  constraint "related_snapshot_sources_snapshot_id_snapshot_source_type_snapshot_source_id_unique" unique (
    "snapshot_id", "snapshot_source_type",
    "snapshot_source_id"
  );

alter table
  "related_snapshot_sources"
add
  primary key ("id");

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_02_22_121138_snapshot_subentities"',
    now(),
    now()
  );