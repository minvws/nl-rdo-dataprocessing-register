create table "avg_responsible_processing_record_service" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "name" text not null,
  "enabled" boolean not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "avg_responsible_processing_record_service" owner TO "dpr";

alter table
  "avg_responsible_processing_record_service"
add
  constraint "av_re_pr_re_se_organisations_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "avg_responsible_processing_record_service"
add
  primary key ("id");

create index "avg_responsible_processing_record_service_name_index" on "avg_responsible_processing_record_service" ("name");

create table "wpg_processing_record_service" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "name" text not null,
  "enabled" boolean not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "wpg_processing_record_service" owner TO "dpr";

alter table
  "wpg_processing_record_service"
add
  constraint "wpg_pro_rec_ser_organisations_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "wpg_processing_record_service"
add
  primary key ("id");

create index "wpg_processing_record_service_name_index" on "wpg_processing_record_service" ("name");

create table "avg_goal_legal_bases" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "name" text not null,
  "enabled" boolean not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "avg_goal_legal_bases" owner TO "dpr";

alter table
  "avg_goal_legal_bases"
add
  constraint "avg_goa_leg_bas_organisations_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "avg_goal_legal_bases"
add
  primary key ("id");

create index "avg_goal_legal_bases_name_index" on "avg_goal_legal_bases" ("name");

create table "contact_person_positions" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "name" text not null,
  "enabled" boolean not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "contact_person_positions" owner TO "dpr";

alter table
  "contact_person_positions"
add
  constraint "conta_perso_posit_organisations_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "contact_person_positions"
add
  primary key ("id");

create index "contact_person_positions_name_index" on "contact_person_positions" ("name");

create table "responsible_types" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "name" text not null,
  "enabled" boolean not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "responsible_types" owner TO "dpr";

alter table
  "responsible_types"
add
  constraint "responsi_types_organisations_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "responsible_types"
add
  primary key ("id");

create index "responsible_types_name_index" on "responsible_types" ("name");

alter table
  "avg_responsible_processing_records"
drop
  column "service";

alter table
  "avg_responsible_processing_records"
add
  column "avg_responsible_processing_record_service_id" uuid null;

alter table
  "avg_responsible_processing_records"
add
  constraint "avg_res_pro_rec_responsi_types_id_foreign" foreign key (
    "avg_responsible_processing_record_service_id"
  ) references "avg_responsible_processing_record_service" ("id");

alter table
  "wpg_processing_records"
drop
  column "service";

alter table
  "wpg_processing_records"
add
  column "wpg_processing_record_service_id" uuid null;

alter table
  "wpg_processing_records"
add
  constraint "wpg_proce_recor_responsi_types_id_foreign" foreign key (
    "wpg_processing_record_service_id"
  ) references "wpg_processing_record_service" ("id");

alter table
  "avg_goals"
drop
  column "legal_basis";

alter table
  "avg_goals"
add
  column "avg_goal_legal_base_id" uuid null;

alter table
  "avg_goals"
add
  constraint "avg_goals_responsi_types_id_foreign" foreign key ("avg_goal_legal_base_id") references "avg_goal_legal_bases" ("id");

alter table
  "contact_persons"
drop
  column "role";

alter table
  "contact_persons"
add
  column "contact_person_position_id" uuid null;

alter table
  "contact_persons"
add
  constraint "contact_persons_responsi_types_id_foreign" foreign key ("contact_person_position_id") references "contact_person_positions" ("id");

alter table
  "processor_types"
add
  column "enabled" boolean not null;

alter table
  "responsibles"
add
  column "responsible_type_id" uuid null;

alter table
  "responsibles"
add
  constraint "responsibles_responsi_types_id_foreign" foreign key ("responsible_type_id") references "responsible_types" ("id");