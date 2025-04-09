create table "audit_logs" (
  "email" varchar(255) null,
  "context" json null,
  "pii_context" text null,
  "created_at" timestamp(0) without time zone not null,
  "event_code" varchar(255) null,
  "action_code" varchar(255) null,
  "source" varchar(255) null,
  "allowed_admin_view" boolean not null default '0',
  "failed" boolean not null default '0',
  "failed_reason" text null
);

ALTER TABLE
  "audit_logs" owner TO "dpr";

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_05_16_132951_initial_application_model"',
    now(),
    now()
  );