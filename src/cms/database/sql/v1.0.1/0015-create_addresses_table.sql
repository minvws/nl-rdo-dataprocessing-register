create table "addresses" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "addressable_type" varchar(255) not null,
  "addressable_id" uuid not null,
  "address" varchar(255) not null,
  "postal_code" varchar(255) not null,
  "city" varchar(255) not null,
  "country" varchar(255) not null,
  "postbox" varchar(255) not null,
  "postbox_postal_code" varchar(255) not null,
  "postbox_city" varchar(255) not null,
  "postbox_country" varchar(255) not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "addresses" owner TO "dpr";

alter table
  "addresses"
add
  constraint "addresses_organisation_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

create index "addresses_addressable_type_addressable_id_index" on "addresses" (
  "addressable_type", "addressable_id"
);

alter table
  "addresses"
add
  primary key ("id");