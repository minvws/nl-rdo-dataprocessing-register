<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('algorithm_records', static function (Blueprint $table): void {
            $table->index('organisation_id', 'algorithm_records_organisation_id_index');
            $table->index('name', 'algorithm_records_name_index');
        });
        Schema::table('avg_processor_processing_records', static function (Blueprint $table): void {
            $table->index('organisation_id', 'avg_processor_processing_records_organisation_id_index');
            $table->index('name', 'avg_processor_processing_records_name_index');
        });
        Schema::table('avg_responsible_processing_records', static function (Blueprint $table): void {
            $table->index('organisation_id', 'avg_responsible_processing_records_organisation_id_index');
            $table->index('name', 'avg_responsible_processing_records_name_index');
        });
        Schema::table('wpg_processing_records', static function (Blueprint $table): void {
            $table->index('organisation_id', 'wpg_processing_records_organisation_id_index');
            $table->index('name', 'wpg_processing_records_name_index');
        });

        Schema::table('organisation_user', static function (Blueprint $table): void {
            $table->index('organisation_id', 'organisation_user_organisation_id_index');
            $table->index('user_id', 'organisation_user_user_id_index');
        });
        Schema::table('organisations', static function (Blueprint $table): void {
            $table->index('name', 'organisations_name_index');
        });
        Schema::table('users', static function (Blueprint $table): void {
            $table->index('current_organisation_id', 'users_current_organisation_id_index');
            $table->index('name', 'users_name_index');
        });
        Schema::table('user_global_roles', static function (Blueprint $table): void {
            $table->index('user_id', 'user_global_roles_user_id_index');
        });
        Schema::table('user_login_tokens', static function (Blueprint $table): void {
            $table->index('user_id', 'user_login_tokens_user_id_index');
        });
        Schema::table('user_organisation_roles', static function (Blueprint $table): void {
            $table->index('user_id', 'user_organisation_roles_user_id_index');
        });

        Schema::table('addresses', static function (Blueprint $table): void {
            $table->index('organisation_id', 'addresses_organisation_id_index');
        });
        Schema::table('algorithm_meta_schemas', static function (Blueprint $table): void {
            $table->index('organisation_id', 'algorithm_meta_schemas_organisation_id_index');
        });
        Schema::table('algorithm_publication_categories', static function (Blueprint $table): void {
            $table->index('organisation_id', 'algorithm_publication_categories_organisation_id_index');
        });
        Schema::table('algorithm_statuses', static function (Blueprint $table): void {
            $table->index('organisation_id', 'algorithm_statuses_organisation_id_index');
        });
        Schema::table('algorithm_themes', static function (Blueprint $table): void {
            $table->index('organisation_id', 'algorithm_themes_organisation_id_index');
        });
        Schema::table('avg_goal_legal_bases', static function (Blueprint $table): void {
            $table->index('organisation_id', 'avg_goal_legal_bases_organisation_id_index');
        });
        Schema::table('avg_goals', static function (Blueprint $table): void {
            $table->index('goal', 'avg_goals_goal_index');
            $table->index('organisation_id', 'avg_goals_organisation_id_index');
        });
        Schema::table('avg_processor_processing_record_service', static function (Blueprint $table): void {
            $table->index('organisation_id', 'avg_processor_processing_record_service_organisation_id_index');
        });
        Schema::table('avg_responsible_processing_record_service', static function (Blueprint $table): void {
            $table->index('organisation_id', 'avg_responsible_processing_record_service_organisation_id_index');
        });
        Schema::table('categories', static function (Blueprint $table): void {
            $table->index('organisation_id', 'categories_organisation_id_index');
            $table->index('name', 'categories_name_index');
        });
        Schema::table('contact_person_positions', static function (Blueprint $table): void {
            $table->index('organisation_id', 'contact_person_positions_organisation_id_index');
        });
        Schema::table('contact_persons', static function (Blueprint $table): void {
            $table->index('organisation_id', 'contact_persons_organisation_id_index');
            $table->index('name', 'contact_persons_name_index');
            $table->index('email', 'contact_persons_email_index');
        });
        Schema::table('domains', static function (Blueprint $table): void {
            $table->index('organisation_id', 'domains_organisation_id_index');
            $table->index('url', 'domains_url_index');
        });
        Schema::table('media', static function (Blueprint $table): void {
            $table->index('organisation_id', 'media_organisation_id_index');
        });
        Schema::table('processor_types', static function (Blueprint $table): void {
            $table->index('organisation_id', 'processor_types_organisation_id_index');
            $table->index('name', 'processor_types_name_index');
        });
        Schema::table('processors', static function (Blueprint $table): void {
            $table->index('organisation_id', 'processors_organisation_id_index');
            $table->index('name', 'processors_name_index');
            $table->index('email', 'processors_email_index');
        });
        Schema::table('receivers', static function (Blueprint $table): void {
            $table->index('organisation_id', 'receivers_organisation_id_index');
            $table->index('description', 'receivers_description_index');
        });
        Schema::table('remarks', static function (Blueprint $table): void {
            $table->index('organisation_id', 'remarks_organisation_id_index');
        });
        Schema::table('responsible_types', static function (Blueprint $table): void {
            $table->index('organisation_id', 'responsible_types_organisation_id_index');
        });
        Schema::table('responsibles', static function (Blueprint $table): void {
            $table->index('organisation_id', 'responsibles_organisation_id_index');
            $table->index('name', 'responsibles_name_index');
            $table->index('email', 'responsibles_email_index');
        });
        Schema::table('snapshot_transitions', static function (Blueprint $table): void {
            $table->index('snapshot_id', 'snapshot_transitions_snapshot_id_index');
        });
        Schema::table('snapshots', static function (Blueprint $table): void {
            $table->index('organisation_id', 'snapshots_organisation_id_index');
        });
        Schema::table('stakeholders', static function (Blueprint $table): void {
            $table->index('organisation_id', 'stakeholders_organisation_id_index');
            $table->index('description', 'stakeholders_description_index');
        });
        Schema::table('systems', static function (Blueprint $table): void {
            $table->index('organisation_id', 'systems_organisation_id_index');
            $table->index('description', 'systems_description_index');
        });
        Schema::table('tags', static function (Blueprint $table): void {
            $table->index('organisation_id', 'tags_organisation_id_index');
        });
        Schema::table('wpg_goals', static function (Blueprint $table): void {
            $table->index('organisation_id', 'wpg_goals_organisation_id_index');
            $table->index('description', 'wpg_goals_description_index');
        });
        Schema::table('wpg_processing_record_service', static function (Blueprint $table): void {
            $table->index('organisation_id', 'wpg_processing_record_service_organisation_id_index');
        });

        Schema::dropIfExists('personal_access_tokens');
    }
};
