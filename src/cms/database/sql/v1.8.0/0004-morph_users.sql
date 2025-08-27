create table "user_relatables" (
  "user_id" uuid not null,
  "user_relatable_type" varchar(255) not null,
  "user_relatable_id" uuid not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "user_relatables" owner TO "dpr";

alter table
  "user_relatables"
add
  constraint "user_relatabl_users_id_foreign" foreign key ("user_id") references "users" ("id") on delete cascade;

create index "user_relatabl_user_relat_type_user_relat_id_index" on "user_relatables" (
  "user_relatable_type", "user_relatable_id"
);

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2025_06_20_173607_morph_users"',
    now(),
    now()
  );