create table "jobs" (
  "id" bigserial not null primary key,
  "queue" varchar(255) not null,
  "payload" text not null,
  "attempts" smallint not null,
  "reserved_at" integer null,
  "available_at" integer not null,
  "created_at" integer not null
);

ALTER TABLE
  "jobs" owner TO "dpr";

create index "jobs_queue_index" on "jobs" ("queue");

drop
  table if exists "failed_jobs";

create table "failed_jobs" (
  "id" bigserial not null primary key,
  "uuid" varchar(255) not null,
  "connection" text not null,
  "queue" text not null,
  "payload" text not null,
  "exception" text not null,
  "failed_at" timestamp(0) without time zone not null default CURRENT_TIMESTAMP
);

ALTER TABLE
  "failed_jobs" owner TO "dpr";

alter table
  "failed_jobs"
add
  constraint "failed_jobs_uuid_unique" unique ("uuid");