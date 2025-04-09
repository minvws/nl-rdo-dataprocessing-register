create table "fg_remarks" (
  "id" uuid not null,
  "fg_remark_relatable_type" varchar(255) not null,
  "fg_remark_relatable_id" uuid not null,
  "body" text null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "fg_remarks" owner TO "dpr";

create index "fg_remarks_fg_remark_relatable_type_fg_remark_relatable_id_index" on "fg_remarks" (
  "fg_remark_relatable_type", "fg_remark_relatable_id"
);

alter table
  "fg_remarks"
add
  primary key ("id");

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_12_13_101849_add_fg_remarks"',
    now(),
    now()
  );