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
        Schema::drop('avg_responsible_processing_record_processor');
        Schema::drop('processor_wpg_processing_record');

        Schema::create('processor_relatables', static function (Blueprint $table): void {
            $processorFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'processors',
                'id',
            );
            $table->foreignUuid('processor_id')
                ->constrained('processors', indexName: $processorFk)
                ->cascadeOnDelete();

            $processorRelatableIx = IndexNameTruncater::index(
                $table->getTable(),
                'processor_relatable_type',
                'processor_relatable_id',
            );
            $table->uuidMorphs('processor_relatable', $processorRelatableIx);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::create('avg_responsible_processing_record_processor', static function (Blueprint $table): void {
            $avgResponsibleProcessingRecordFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'avg_responsible_processing_records',
                'id',
            );
            $table->foreignUuid('avg_responsible_processing_record_id')
                ->constrained('avg_responsible_processing_records', indexName: $avgResponsibleProcessingRecordFk)
                ->cascadeOnDelete();

            $processorFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'processors',
                'id',
            );
            $table->foreignUuid('processor_id')
                ->constrained('processors', indexName: $processorFk)
                ->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::create('processor_wpg_processing_record', static function (Blueprint $table): void {
            $wpgProcessingRecordFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'wpg_processing_records',
                'id',
            );
            $table->foreignUuid('wpg_processing_record_id')
                ->constrained('wpg_processing_records', indexName: $wpgProcessingRecordFk)
                ->cascadeOnDelete();

            $processorFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'processors',
                'id',
            );
            $table->foreignUuid('processor_id')
                ->constrained('processors', indexName: $processorFk)
                ->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::dropIfExists('processor_relatables');
    }
};
