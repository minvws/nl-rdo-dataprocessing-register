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
        Schema::table('wpg_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('responsible_id');
        });

        Schema::drop('avg_processor_processing_record_responsible');
        Schema::drop('avg_responsible_processing_record_responsible');
        Schema::drop('responsible_wpg_processing_record');

        Schema::create('responsible_relatables', static function (Blueprint $table): void {
            $responsibleFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'responsibles',
                'id',
            );
            $table->foreignUuid('responsible_id')
                ->constrained('responsibles', indexName: $responsibleFk)
                ->cascadeOnDelete();

            $responsibleRelatableIx = IndexNameTruncater::index(
                $table->getTable(),
                'responsible_relatable_type',
                'responsible_relatable_id',
            );
            $table->uuidMorphs('responsible_relatable', $responsibleRelatableIx);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::create('avg_processor_processing_record_responsible', static function (Blueprint $table): void {
            $avgProcessorProcessingRecordFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'avg_processor_processing_records',
                'id',
            );
            $table->foreignUuid('avg_processor_processing_record_id')
                ->constrained('avg_processor_processing_records', $avgProcessorProcessingRecordFk)
                ->cascadeOnDelete();

            $responsibleFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'responsibles',
                'id',
            );
            $table->foreignUuid('responsible_id')
                ->constrained('responsibles', $responsibleFk)
                ->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::create('avg_responsible_processing_record_responsible', static function (Blueprint $table): void {
            $avgResponsibleProcessingRecordFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'avg_responsible_processing_records',
                'id',
            );
            $table->foreignUuid('avg_responsible_processing_record_id')
                ->constrained('avg_responsible_processing_records', indexName: $avgResponsibleProcessingRecordFk)
                ->cascadeOnDelete();

            $responsibleFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'responsibles',
                'id',
            );
            $table->foreignUuid('responsible_id')
                ->constrained('responsibles', indexName: $responsibleFk)
                ->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::create('responsible_wpg_processing_record', static function (Blueprint $table): void {
            $wpgProcessingRecordFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'wpg_processing_records',
                'id',
            );
            $table->foreignUuid('wpg_processing_record_id')
                ->constrained('wpg_processing_records', indexName: $wpgProcessingRecordFk)
                ->cascadeOnDelete();

            $responsibleFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'responsibles',
                'id',
            );
            $table->foreignUuid('responsible_id')
                ->constrained('responsibles', indexName: $responsibleFk)
                ->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::table('wpg_processing_records', static function (Blueprint $table): void {
            $responsibleFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'responsibles',
                'id',
            );
            $table->foreignUuid('responsible_id')
                ->nullable()
                ->constrained('responsibles', indexName: $responsibleFk)
                ->cascadeOnDelete();
        });

        Schema::dropIfExists('responsible_relatables');
    }
};
