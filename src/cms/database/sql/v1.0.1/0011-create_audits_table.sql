create table "audits" (
  "id" uuid not null,
  "user_type" varchar(255) null,
  "user_id" uuid null,
  "event" varchar(255) not null,
  "auditable_type" varchar(255) not null,
  "auditable_id" uuid not null,
  "old_values" text null,
  "new_values" text null,
  "url" text null,
  "ip_address" inet null,
  "user_agent" varchar(1023) null,
  "tags" varchar(255) null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "audits" owner TO "dpr";

create index "audits_auditable_type_auditable_id_index" on "audits" (
  "auditable_type", "auditable_id"
);

create index "audits_user_id_user_type_index" on "audits" ("user_id", "user_type");

alter table
  "audits"
add
  primary key ("id");