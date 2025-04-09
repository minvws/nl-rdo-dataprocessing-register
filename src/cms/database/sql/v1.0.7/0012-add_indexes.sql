create index "algorithm_records_organisation_id_index" on "algorithm_records" ("organisation_id");

create index "algorithm_records_name_index" on "algorithm_records" ("name");

create index "avg_processor_processing_records_organisation_id_index" on "avg_processor_processing_records" ("organisation_id");

create index "avg_processor_processing_records_name_index" on "avg_processor_processing_records" ("name");

create index "avg_responsible_processing_records_organisation_id_index" on "avg_responsible_processing_records" ("organisation_id");

create index "avg_responsible_processing_records_name_index" on "avg_responsible_processing_records" ("name");

create index "wpg_processing_records_organisation_id_index" on "wpg_processing_records" ("organisation_id");

create index "wpg_processing_records_name_index" on "wpg_processing_records" ("name");

create index "organisation_user_organisation_id_index" on "organisation_user" ("organisation_id");

create index "organisation_user_user_id_index" on "organisation_user" ("user_id");

create index "organisations_name_index" on "organisations" ("name");

create index "users_current_organisation_id_index" on "users" ("current_organisation_id");

create index "users_name_index" on "users" ("name");

create index "user_global_roles_user_id_index" on "user_global_roles" ("user_id");

create index "user_login_tokens_user_id_index" on "user_login_tokens" ("user_id");

create index "user_organisation_roles_user_id_index" on "user_organisation_roles" ("user_id");

create index "addresses_organisation_id_index" on "addresses" ("organisation_id");

create index "algorithm_meta_schemas_organisation_id_index" on "algorithm_meta_schemas" ("organisation_id");

create index "algorithm_publication_categories_organisation_id_index" on "algorithm_publication_categories" ("organisation_id");

create index "algorithm_statuses_organisation_id_index" on "algorithm_statuses" ("organisation_id");

create index "algorithm_themes_organisation_id_index" on "algorithm_themes" ("organisation_id");

create index "avg_goal_legal_bases_organisation_id_index" on "avg_goal_legal_bases" ("organisation_id");

create index "avg_goals_goal_index" on "avg_goals" ("goal");

create index "avg_goals_organisation_id_index" on "avg_goals" ("organisation_id");

create index "avg_processor_processing_record_service_organisation_id_index" on "avg_processor_processing_record_service" ("organisation_id");

create index "avg_responsible_processing_record_service_organisation_id_index" on "avg_responsible_processing_record_service" ("organisation_id");

create index "categories_organisation_id_index" on "categories" ("organisation_id");

create index "categories_name_index" on "categories" ("name");

create index "contact_person_positions_organisation_id_index" on "contact_person_positions" ("organisation_id");

create index "contact_persons_organisation_id_index" on "contact_persons" ("organisation_id");

create index "contact_persons_name_index" on "contact_persons" ("name");

create index "contact_persons_email_index" on "contact_persons" ("email");

create index "domains_organisation_id_index" on "domains" ("organisation_id");

create index "domains_url_index" on "domains" ("url");

create index "media_organisation_id_index" on "media" ("organisation_id");

create index "processor_types_organisation_id_index" on "processor_types" ("organisation_id");

create index "processor_types_name_index" on "processor_types" ("name");

create index "processors_organisation_id_index" on "processors" ("organisation_id");

create index "processors_name_index" on "processors" ("name");

create index "processors_email_index" on "processors" ("email");

create index "receivers_organisation_id_index" on "receivers" ("organisation_id");

create index "receivers_description_index" on "receivers" ("description");

create index "remarks_organisation_id_index" on "remarks" ("organisation_id");

create index "responsible_types_organisation_id_index" on "responsible_types" ("organisation_id");

create index "responsibles_organisation_id_index" on "responsibles" ("organisation_id");

create index "responsibles_name_index" on "responsibles" ("name");

create index "responsibles_email_index" on "responsibles" ("email");

create index "snapshot_transitions_snapshot_id_index" on "snapshot_transitions" ("snapshot_id");

create index "snapshots_organisation_id_index" on "snapshots" ("organisation_id");

create index "stakeholders_organisation_id_index" on "stakeholders" ("organisation_id");

create index "stakeholders_description_index" on "stakeholders" ("description");

create index "systems_organisation_id_index" on "systems" ("organisation_id");

create index "systems_description_index" on "systems" ("description");

create index "tags_organisation_id_index" on "tags" ("organisation_id");

create index "wpg_goals_organisation_id_index" on "wpg_goals" ("organisation_id");

create index "wpg_goals_description_index" on "wpg_goals" ("description");

create index "wpg_processing_record_service_organisation_id_index" on "wpg_processing_record_service" ("organisation_id");

drop
  table if exists "personal_access_tokens";

INSERT INTO "admin_log_entries" (
  "message", "created_at", "updated_at"
)
values
  (
    'Migrated "2024_02_01_150246_add_indexes"',
    now(),
    now()
  );