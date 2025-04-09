create table "remarks" (
  "id" uuid not null,
  "user_id" uuid null,
  "organisation_id" uuid null,
  "remark_relatable_type" varchar(255) not null,
  "remark_relatable_id" uuid not null,
  "body" text not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "remarks" owner TO "dpr";

alter table
  "remarks"
add
  constraint "remarks_users_id_foreign" foreign key ("user_id") references "users" ("id");

alter table
  "remarks"
add
  constraint "remarks_organisations_id_foreign" foreign key ("organisation_id") references "organisations" ("id");

create index "remarks_remar_relat_type_remar_relat_id_index" on "remarks" (
  "remark_relatable_type", "remark_relatable_id"
);