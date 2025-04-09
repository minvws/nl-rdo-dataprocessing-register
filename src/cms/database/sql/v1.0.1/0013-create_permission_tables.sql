create table "permissions" (
  "id" uuid not null,
  "name" varchar(255) not null,
  "guard_name" varchar(255) not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "permissions" owner TO "dpr";

alter table
  "permissions"
add
  constraint "permissions_name_guard_name_unique" unique ("name", "guard_name");

alter table
  "permissions"
add
  primary key ("id");

create table "roles" (
  "id" uuid not null,
  "name" varchar(255) not null,
  "guard_name" varchar(255) not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "roles" owner TO "dpr";

alter table
  "roles"
add
  constraint "roles_name_guard_name_unique" unique ("name", "guard_name");

alter table
  "roles"
add
  primary key ("id");

create table "model_has_permissions" (
  "permission_id" uuid not null,
  "model_type" varchar(255) not null,
  "model_id" uuid not null
);

ALTER TABLE
  "model_has_permissions" owner TO "dpr";

create index "model_has_permissions_model_id_model_type_index" on "model_has_permissions" ("model_id", "model_type");

alter table
  "model_has_permissions"
add
  constraint "model_has_permissions_permission_id_foreign" foreign key ("permission_id") references "permissions" ("id") on delete cascade;

alter table
  "model_has_permissions"
add
  primary key (
    "permission_id", "model_id", "model_type"
  );

create table "model_has_roles" (
  "role_id" uuid not null,
  "model_type" varchar(255) not null,
  "model_id" uuid not null
);

ALTER TABLE
  "model_has_roles" owner TO "dpr";

create index "model_has_roles_model_id_model_type_index" on "model_has_roles" ("model_id", "model_type");

alter table
  "model_has_roles"
add
  constraint "model_has_roles_role_id_foreign" foreign key ("role_id") references "roles" ("id") on delete cascade;

alter table
  "model_has_roles"
add
  primary key (
    "role_id", "model_id", "model_type"
  );

create table "role_has_permissions" (
  "permission_id" uuid not null, "role_id" uuid not null
);

ALTER TABLE
  "role_has_permissions" owner TO "dpr";

alter table
  "role_has_permissions"
add
  constraint "role_has_permissions_permission_id_foreign" foreign key ("permission_id") references "permissions" ("id") on delete cascade;

alter table
  "role_has_permissions"
add
  constraint "role_has_permissions_role_id_foreign" foreign key ("role_id") references "roles" ("id") on delete cascade;

alter table
  "role_has_permissions"
add
  primary key ("permission_id", "role_id");