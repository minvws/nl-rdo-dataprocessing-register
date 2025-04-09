insert into "responsible_legal_entity" (
  "id", "name", "created_at", "updated_at"
)
values
  (
    '2ff9b3de-deae-346f-8957-c58d79871cf5',
    'Minister', '2024-03-08 09:12:53',
    '2024-03-08 09:12:53'
  );

insert into "responsible_legal_entity" (
  "id", "name", "created_at", "updated_at"
)
values
  (
    '9ecfeabf-bae5-381d-98f1-f10ae51dbead',
    'Zelfstandig', '2024-03-08 09:12:53',
    '2024-03-08 09:12:53'
  );

UPDATE
  organisations
SET
  responsible_legal_entity_id = (
    SELECT
      id
    FROM
      responsible_legal_entity
    WHERE
      name = 'Minister'
  );

ALTER TABLE
  organisations ALTER responsible_legal_entity_id
SET
  NOT NULL;

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_03_01_094112_add_responsible_legal_entity_2"',
    now(),
    now()
  );