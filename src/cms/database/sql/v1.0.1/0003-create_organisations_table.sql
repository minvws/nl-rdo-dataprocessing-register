create table "organisations" (
  "id" uuid not null,
  "name" varchar(255) not null,
  "slug" varchar(255) not null,
  "created_at" timestamp(0) without time zone null,
  "updated_at" timestamp(0) without time zone null
);

ALTER TABLE
  "organisations" owner TO "dpr";

alter table
  "organisations"
add
  primary key ("id");

alter table
  "organisations"
add
  constraint "organisations_slug_unique" unique ("slug");