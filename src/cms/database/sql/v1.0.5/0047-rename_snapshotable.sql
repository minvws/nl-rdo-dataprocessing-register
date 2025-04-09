drop
  index "snapshots_snapshotable_type_snapshotable_id_index";

alter table
  "snapshots"
drop
  column "snapshotable_type";

alter table
  "snapshots"
drop
  column "snapshotable_id";

alter table
  "snapshots"
add
  column "snapshot_relatable_type" varchar(255) null;

alter table
  "snapshots"
add
  column "snapshot_relatable_id" uuid null;

create index "snapshots_snaps_relat_type_snaps_relat_id_index" on "snapshots" (
  "snapshot_relatable_type", "snapshot_relatable_id"
);