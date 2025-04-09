drop
  index "snapshots_snaps_relat_type_snaps_relat_id_index";

drop
  index "snapshots_relatable_state";

alter table
  "snapshots"
drop
  column "snapshot_relatable_id";

alter table
  "snapshots"
drop
  column "snapshot_relatable_type";

alter table
  "snapshots"
add
  column "snapshot_source_type" varchar(255) null;

alter table
  "snapshots"
add
  column "snapshot_source_id" uuid null;

create index "snapshots_snaps_sourc_type_snaps_sourc_id_index" on "snapshots" (
  "snapshot_source_type", "snapshot_source_id"
);

CREATE UNIQUE INDEX snapshots_source_state ON snapshots (
  snapshot_source_type, snapshot_source_id,
  state
)
WHERE
  state != 'obsolete';

;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_02_20_121330_rename_snapshot_relatable"',
    now(),
    now()
  );