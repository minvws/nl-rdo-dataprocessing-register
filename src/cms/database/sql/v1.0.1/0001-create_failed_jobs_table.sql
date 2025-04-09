create table "failed_jobs" (
  "id" uuid not null,
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
  primary key ("id");