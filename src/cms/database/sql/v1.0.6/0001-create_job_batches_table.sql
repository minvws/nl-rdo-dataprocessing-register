create table "job_batches" (
  "id" varchar(255) not null,
  "name" varchar(255) not null,
  "total_jobs" integer not null,
  "pending_jobs" integer not null,
  "failed_jobs" integer not null,
  "failed_job_ids" text not null,
  "options" text null,
  "cancelled_at" integer null,
  "created_at" integer not null,
  "finished_at" integer null
);

ALTER TABLE
  "job_batches" owner TO "dpr";

alter table
  "job_batches"
add
  primary key ("id");