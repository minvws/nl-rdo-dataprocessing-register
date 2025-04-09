alter table
  "media"
add
  column "content_hash" varchar(64) null;

alter table
  "media"
add
  column "validated_at" timestamp(0) without time zone null;

alter table
  "media"
add
  column "organisation_id" uuid null;

alter table
  "media"
add
  constraint "media_organisation_id_foreign" foreign key ("organisation_id") references "organisations" ("id") on delete cascade;