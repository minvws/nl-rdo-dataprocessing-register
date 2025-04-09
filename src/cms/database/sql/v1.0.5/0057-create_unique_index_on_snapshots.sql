CREATE UNIQUE INDEX snapshots_relatable_state ON snapshots (
  snapshot_relatable_type, snapshot_relatable_id,
  state
)
WHERE
  state != 'obsolete';

;