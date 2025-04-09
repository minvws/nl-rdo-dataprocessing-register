alter table
  "wpg_goals"
drop
  column "wpg_processing_record_id";

create table "wpg_goal_relatables" (
  "wpg_goal_id" uuid not null,
  "wpg_goal_relatable_type" varchar(255) not null,
  "wpg_goal_relatable_id" uuid not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "wpg_goal_relatables" owner TO "dpr";

alter table
  "wpg_goal_relatables"
add
  constraint "wpg_goal_relat_avg_goals_id_foreign" foreign key ("wpg_goal_id") references "wpg_goals" ("id") on delete cascade;

create index "wpg_goal_relat_wpg_goa_rel_typ_wpg_goa_rel_id_index" on "wpg_goal_relatables" (
  "wpg_goal_relatable_type", "wpg_goal_relatable_id"
);

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_02_23_121347_fix_wpg_goals"',
    now(),
    now()
  );