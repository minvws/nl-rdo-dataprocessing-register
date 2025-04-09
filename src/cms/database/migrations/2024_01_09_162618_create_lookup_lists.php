<?php

declare(strict_types=1);

use App\Services\SqlExport\IndexNameTruncater;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->createLookupList('avg_responsible_processing_record_service');
        $this->createLookupList('wpg_processing_record_service');
        $this->createLookupList('avg_goal_legal_bases');
        $this->createLookupList('contact_person_positions');
        $this->createLookupList('responsible_types');

        $this->replaceFieldWithLookupField(
            'avg_responsible_processing_records',
            'service',
            'avg_responsible_processing_record_service_id',
            'avg_responsible_processing_record_service',
        );
        $this->replaceFieldWithLookupField(
            'wpg_processing_records',
            'service',
            'wpg_processing_record_service_id',
            'wpg_processing_record_service',
        );
        $this->replaceFieldWithLookupField('avg_goals', 'legal_basis', 'avg_goal_legal_base_id', 'avg_goal_legal_bases');
        $this->replaceFieldWithLookupField('contact_persons', 'role', 'contact_person_position_id', 'contact_person_positions');

        Schema::table('processor_types', static function (Blueprint $table): void {
            $table->boolean('enabled');
        });

        Schema::table('responsibles', static function (Blueprint $table): void {
            $table->foreignUuid('responsible_type_id')
                ->nullable()
                ->constrained('responsible_types', indexName: IndexNameTruncater::foreignKey(
                    $table->getTable(),
                    'responsible_types',
                    'id',
                ));
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avg_responsible_processing_record_service');
        Schema::dropIfExists('wpg_processing_recordsservice');
        Schema::dropIfExists('avg_goal_legal_bases');

        $this->undoLookupField('avg_responsible_processing_records', 'service', 'avg_responsible_processing_record_service_id');
        $this->undoLookupField('wpg_processing_records', 'service', 'wpg_processing_record_service_id');
        $this->undoLookupField('avg_goals', 'legal_basis', 'avg_goal_legal_base_id');
    }

    private function createLookupList(string $tableName): void
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

    private function replaceFieldWithLookupField(
        string $tableName,
        string $dropColumnName,
        string $foreignColumnName,
        string $foreignTableName,
    ): void {
        Schema::table($tableName, static function (Blueprint $table) use ($dropColumnName, $foreignColumnName, $foreignTableName): void {
            $table->dropColumn($dropColumnName);

            $table->foreignUuid($foreignColumnName)
                ->nullable()
                ->constrained($foreignTableName, indexName: IndexNameTruncater::foreignKey(
                    $table->getTable(),
                    'responsible_types',
                    'id',
                ));
        });
    }

    private function undoLookupField(string $tableName, string $originalFieldName, string $foreignFieldName): void
    {
        Schema::table($tableName, static function (Blueprint $table) use ($originalFieldName, $foreignFieldName): void {
            $table->string($originalFieldName);

            $table->dropForeign($foreignFieldName);
        });
    }
};
