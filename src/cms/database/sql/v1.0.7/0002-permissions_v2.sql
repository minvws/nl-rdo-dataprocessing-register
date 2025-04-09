drop
  table "role_has_permissions";

drop
  table "model_has_roles";

drop
  table "model_has_permissions";

drop
  table "roles";

drop
  table "permissions";

create table "user_global_roles" (
  "id" uuid not null,
  "user_id" uuid not null,
  "role" varchar(255) not null
);

ALTER TABLE
  "user_global_roles" owner TO "dpr";

alter table
  "user_global_roles"
add
  constraint "user_globa_roles_users_id_foreign" foreign key ("user_id") references "users" ("id") on delete cascade;

alter table
  "user_global_roles"
add
  constraint "user_global_roles_user_id_role_unique" unique ("user_id", "role");

alter table
  "user_global_roles"
add
  primary key ("id");

create table "user_organisation_roles" (
  "id" uuid not null,
  "organisation_id" uuid not null,
  "user_id" uuid not null,
  "role" varchar(255) not null
);

ALTER TABLE
  "user_organisation_roles" owner TO "dpr";

alter table
  "user_organisation_roles"
add
  constraint "user_organ_roles_organisations_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;

alter table
  "user_organisation_roles"
add
  constraint "user_organ_roles_users_id_foreign" foreign key ("user_id") references "users" ("id") on delete cascade;

alter table
  "user_organisation_roles"
add
  constraint "user_organisation_roles_organisation_id_user_id_role_unique" unique (
    "organisation_id", "user_id", "role"
  );

alter table
  "user_organisation_roles"
add
  primary key ("id");

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_01_16_161117_permissions_v2"',
    now(),
    now()
  );