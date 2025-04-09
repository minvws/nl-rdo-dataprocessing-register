create table "avg_goalables" (
  "avg_goal_id" uuid not null,
  "avg_goalable_type" varchar(255) not null,
  "avg_goalable_id" uuid not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "avg_goalables" owner TO "dpr";

alter table
  "avg_goalables"
add
  constraint "avg_goalables_avg_goal_id_foreign" foreign key ("avg_goal_id") references "avg_goals" ("id") on delete cascade;

create index "avg_goalables_avg_goalable_type_avg_goalable_id_index" on "avg_goalables" (
  "avg_goalable_type", "avg_goalable_id"
);