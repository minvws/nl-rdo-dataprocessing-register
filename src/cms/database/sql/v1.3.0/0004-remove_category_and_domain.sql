drop
  table if exists "category_relatables";

drop
  table if exists "categories";

drop
  table if exists "domains";

select
  *
from
  "snapshots"
where
  (
    "snapshot_source_type" = 'App\Models\Category'
  );

select
  *
from
  "snapshots"
where
  (
    "snapshot_source_type" = 'App\Models\Domain'
  );

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_05_22_132440_remove_category_and_domain"',
    now(),
    now()
  );