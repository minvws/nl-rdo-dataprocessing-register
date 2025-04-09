create table "sessions" (
  "id" varchar(255) not null,
  "user_id" uuid null,
  "ip_address" varchar(45) null,
  "user_agent" text null,
  "payload" text not null,
  "last_activity" integer not null
);

ALTER TABLE
  "sessions" owner TO "dpr";

alter table
  "sessions"
add
  constraint "sessions_user_id_foreign" foreign key ("user_id") references "users" ("id");

alter table
  "sessions"
add
  primary key ("id");

create index "sessions_user_id_index" on "sessions" ("user_id");

create index "sessions_last_activity_index" on "sessions" ("last_activity");