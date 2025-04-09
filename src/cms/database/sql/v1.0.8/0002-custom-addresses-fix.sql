ALTER TABLE
    addresses ALTER address
    DROP
    NOT NULL;

ALTER TABLE
    addresses ALTER postal_code
    DROP
    NOT NULL;

ALTER TABLE
    addresses ALTER city
    DROP
    NOT NULL;

ALTER TABLE
    addresses ALTER country
    DROP
    NOT NULL;

ALTER TABLE
    addresses ALTER postbox
    DROP
    NOT NULL;

ALTER TABLE
    addresses ALTER postbox_postal_code
    DROP
    NOT NULL;

ALTER TABLE
    addresses ALTER postbox_city
    DROP
    NOT NULL;

ALTER TABLE
    addresses ALTER postbox_country
    DROP
    NOT NULL;

insert into "admin_log_entries" (
    "message", "created_at", "updated_at"
)
values
    (
        'Migrated "0002-custom-addresses-fix"',
        now(), now()
    );
