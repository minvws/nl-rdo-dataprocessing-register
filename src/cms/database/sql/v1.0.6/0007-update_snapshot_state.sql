update
  "snapshots"
set
  "state" = 'established'
where
  ("state" = 'published');

update
  "snapshot_transitions"
set
  "state" = 'established'
where
  ("state" = 'published');