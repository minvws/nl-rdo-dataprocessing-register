drop
  table if exists "avg_goalables";

create table "avg_goal_relatables" (
  "avg_goal_id" uuid not null,
  "avg_goal_relatable_type" varchar(255) not null,
  "avg_goal_relatable_id" uuid not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "avg_goal_relatables" owner TO "dpr";

alter table
  "avg_goal_relatables"
add
  constraint "avg_goal_relat_avg_goals_id_foreign" foreign key ("avg_goal_id") references "avg_goals" ("id") on delete cascade;

create index "avg_goal_relat_avg_goa_rel_typ_avg_goa_rel_id_index" on "avg_goal_relatables" (
  "avg_goal_relatable_type", "avg_goal_relatable_id"
);