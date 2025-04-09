truncate "snapshot_approvals" restart identity cascade;

truncate "snapshot_approval_logs" restart identity cascade;

truncate "snapshot_data" restart identity cascade;

truncate "snapshot_transitions" restart identity cascade;

truncate "snapshots" restart identity cascade;

alter table
  "snapshot_data"
drop
  column "private_frontmatter";

alter table
  "snapshot_data"
drop
  column "private_markdown";

alter table
  "snapshot_data"
drop
  column "public_frontmatter";

alter table
  "snapshot_data"
add
  column "private_markdown" text null;

alter table
  "snapshot_data"
add
  column "public_frontmatter" text not null;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_03_21_080544_alter_snapshot_data"',
    now(),
    now()
  );