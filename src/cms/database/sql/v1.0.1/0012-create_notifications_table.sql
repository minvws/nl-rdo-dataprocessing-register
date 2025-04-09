create table "notifications" (
  "id" uuid not null,
  "type" varchar(255) not null,
  "notifiable_type" varchar(255) not null,
  "notifiable_id" uuid not null,
  "data" json not null,
  "read_at" timestamp(0) without time zone null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "notifications" owner TO "dpr";

create index "notifications_notifiable_type_notifiable_id_index" on "notifications" (
  "notifiable_type", "notifiable_id"
);

alter table
  "notifications"
add
  primary key ("id");