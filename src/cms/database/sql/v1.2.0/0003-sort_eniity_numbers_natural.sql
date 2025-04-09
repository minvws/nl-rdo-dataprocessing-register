CREATE COLLATION IF NOT EXISTS numeric (
  provider = icu, locale = 'en-u-kn-true'
);

;

ALTER TABLE
  entity_numbers ALTER COLUMN number TYPE character varying(255) COLLATE numeric;

;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_05_02_142659_sort_eniity_numbers_natural"',
    now(),
    now()
  );