create table "snapshots" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "snapshotable_type" varchar(255) not null,
  "snapshotable_id" uuid not null,
  "name" varchar(255) not null,
  "version" integer not null,
  "state" varchar(255) not null,
  "replaced_at" timestamp(0) without time zone null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "snapshots" owner TO "dpr";

alter table
  "snapshots"
add
  constraint "snapshots_organisation_id_foreign" foreign key ("organisation_id") references "organisations" ("id");

create index "snapshots_snapshotable_type_snapshotable_id_index" on "snapshots" (
  "snapshotable_type", "snapshotable_id"
);

alter table
  "snapshots"
add
  constraint "snapshots_snapshotable_type_snapshotable_id_version_unique" unique (
    "snapshotable_type", "snapshotable_id",
    "version"
  );

alter table
  "snapshots"
add
  primary key ("id");

create index "snapshots_version_index" on "snapshots" ("version");

create table "snapshot_transitions" (
  "id" uuid not null,
  "snapshot_id" uuid not null,
  "created_by" uuid not null,
  "state" varchar(255) not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "snapshot_transitions" owner TO "dpr";

alter table
  "snapshot_transitions"
add
  constraint "snapshot_transitions_snapshot_id_foreign" foreign key ("snapshot_id") references "snapshots" ("id");

alter table
  "snapshot_transitions"
add
  constraint "snapshot_transitions_created_by_foreign" foreign key ("created_by") references "users" ("id");

alter table
  "snapshot_transitions"
add
  primary key ("id");