<?php

declare(strict_types=1);

use App\Services\SqlExport\IndexNameTruncater;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $this->createLookupTable('algorithm_themes');
        $this->createLookupTable('algorithm_statuses');
        $this->createLookupTable('algorithm_publication_categories');
        $this->createLookupTable('algorithm_meta_schemas');

        Schema::create('algorithm_records', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organisation_id')
                ->constrained('organisations', indexName: IndexNameTruncater::foreignKey(
                    $table->getTable(),
                    'organisations',
                    'id',
                ))
                ->cascadeOnDelete();
            $table->foreignUuid('algorithm_theme_id')
                ->nullable()
                ->constrained('algorithm_themes', indexName: IndexNameTruncater::foreignKey(
                    $table->getTable(),
                    'algorithm_themes',
                    'id',
                ));
            $table->foreignUuid('algorithm_status_id')
                ->nullable()
                ->constrained('algorithm_statuses', indexName: IndexNameTruncater::foreignKey(
                    $table->getTable(),
                    'algorithm_statuses',
                    'id',
                ));
            $table->foreignUuid('algorithm_publication_category_id')
                ->nullable()
                ->constrained('algorithm_publication_categories', indexName: IndexNameTruncater::foreignKey(
                    $table->getTable(),
                    'algorithm_publication_categories',
                    'id',
                ));
            $table->foreignUuid('algorithm_meta_schema_id')
                ->nullable()
                ->constrained('algorithm_meta_schemas', indexName: IndexNameTruncater::foreignKey(
                    $table->getTable(),
                    'algorithm_meta_schemas',
                    'id',
                ));

            $table->string('import_id')->nullable();
            $table->string('name');
            $table->string('number');
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('contact_data')->nullable();
            $table->string('source_link')->nullable();

            $table->text('resp_goal_and_impact')->nullable();
            $table->text('resp_considerations')->nullable();
            $table->text('resp_human_intervention')->nullable();
            $table->text('resp_risk_analysis')->nullable();
            $table->text('resp_legal_base')->nullable();
            $table->string('resp_legal_base_link')->nullable();
            $table->string('resp_processor_registry_link')->nullable();
            $table->text('resp_impact_tests')->nullable();
            $table->text('resp_impact_test_links')->nullable();
            $table->text('resp_impact_tests_description')->nullable();

            $table->text('oper_data')->nullable();
            $table->text('oper_links')->nullable();
            $table->text('oper_technical_operation')->nullable();
            $table->text('oper_supplier')->nullable();
            $table->string('oper_source_code_link')->nullable();

            $table->string('meta_lang')->nullable();
            $table->string('meta_national_id')->nullable();
            $table->string('meta_source_id')->nullable();
            $table->text('meta_tags')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('algorithm_records');
        Schema::dropIfExists('algorithm_themes');
        Schema::dropIfExists('algorithm_statuses');
        Schema::dropIfExists('algorithm_publication_categories');
        Schema::dropIfExists('algorithm_meta_schemas');
    }

    private function createLookupTable(string $tableName): void
    {
        Schema::create($tableName, static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organisation_id')
                ->constrained('organisations', indexName: IndexNameTruncater::foreignKey(
                    $table->getTable(),
                    'organisations',
                    'id',
                ))
                ->cascadeOnDelete();

            $table->text('name')->index();
            $table->boolean('enabled');

            $table->timestamps();
        });
    }
};
