create table "snapshot_approvals" (
  "id" uuid not null,
  "snapshot_id" uuid not null,
  "requested_by" uuid null,
  "assigned_to" uuid not null,
  "status" varchar(255) not null default 'unknown',
  "notified_at" timestamp(0) without time zone null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "snapshot_approvals" owner TO "dpr";

alter table
  "snapshot_approvals"
add
  constraint "snapshot_approvals_snapshot_id_foreign" foreign key ("snapshot_id") references "snapshots" ("id") on delete cascade;

alter table
  "snapshot_approvals"
add
  constraint "snapshot_approvals_requested_by_foreign" foreign key ("requested_by") references "users" ("id");

alter table
  "snapshot_approvals"
add
  constraint "snapshot_approvals_assigned_to_foreign" foreign key ("assigned_to") references "users" ("id") on delete cascade;

alter table
  "snapshot_approvals"
add
  constraint "snapshot_approvals_snapshot_id_assigned_to_unique" unique ("snapshot_id", "assigned_to");

alter table
  "snapshot_approvals"
add
  primary key ("id");

create table "snapshot_approval_logs" (
  "id" uuid not null,
  "snapshot_id" uuid not null,
  "user_id" uuid not null,
  "message" json not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "snapshot_approval_logs" owner TO "dpr";

alter table
  "snapshot_approval_logs"
add
  constraint "snapshot_approval_logs_snapshot_id_foreign" foreign key ("snapshot_id") references "snapshots" ("id") on delete cascade;

alter table
  "snapshot_approval_logs"
add
  constraint "snapshot_approval_logs_user_id_foreign" foreign key ("user_id") references "users" ("id");

alter table
  "snapshot_approval_logs"
add
  primary key ("id");

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_02_02_100036_snapshot_approvals"',
    now(),
    now()
  );