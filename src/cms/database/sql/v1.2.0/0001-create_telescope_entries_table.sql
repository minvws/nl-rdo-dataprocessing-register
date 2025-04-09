create table "telescope_entries" (
  "sequence" bigserial not null primary key,
  "uuid" uuid not null,
  "batch_id" uuid not null,
  "family_hash" varchar(255) null,
  "should_display_on_index" boolean not null default '1',
  "type" varchar(20) not null,
  "content" text not null,
  "created_at" timestamp(0) without time zone null
);

ALTER TABLE
  "telescope_entries" owner TO "dpr";

alter table
  "telescope_entries"
add
  constraint "telescope_entries_uuid_unique" unique ("uuid");

create index "telescope_entries_batch_id_index" on "telescope_entries" ("batch_id");

create index "telescope_entries_family_hash_index" on "telescope_entries" ("family_hash");

create index "telescope_entries_created_at_index" on "telescope_entries" ("created_at");

create index "telescope_entries_type_should_display_on_index_index" on "telescope_entries" (
  "type", "should_display_on_index"
);

create table "telescope_entries_tags" (
  "entry_uuid" uuid not null,
  "tag" varchar(255) not null
);

ALTER TABLE
  "telescope_entries_tags" owner TO "dpr";

alter table
  "telescope_entries_tags"
add
  primary key ("entry_uuid", "tag");

create index "telescope_entries_tags_tag_index" on "telescope_entries_tags" ("tag");

alter table
  "telescope_entries_tags"
add
  constraint "telescope_entries_tags_entry_uuid_foreign" foreign key ("entry_uuid") references "telescope_entries" ("uuid") on delete cascade;

create table "telescope_monitoring" (
  "tag" varchar(255) not null
);

ALTER TABLE
  "telescope_monitoring" owner TO "dpr";

alter table
  "telescope_monitoring"
add
  primary key ("tag");

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_04_25_100000_create_telescope_entries_table"',
    now(),
    now()
  );