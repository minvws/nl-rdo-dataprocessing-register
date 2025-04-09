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
        Schema::drop('avg_processor_processing_record_system');
        Schema::drop('avg_responsible_processing_record_system');
        Schema::drop('system_wpg_processing_record');

        Schema::create('system_relatables', static function (Blueprint $table): void {
            $systemFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'systems',
                'id',
            );
            $table->foreignUuid('system_id')
                ->constrained('systems', indexName: $systemFk)
                ->cascadeOnDelete();

            $systemRelatableIx = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'system_relatable_type',
                'system_relatable_id',
            );
            $table->uuidMorphs('system_relatable', $systemRelatableIx);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::create('avg_processor_processing_record_system', static function (Blueprint $table): void {
            $avgProcessorProcessingRecordFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'avg_processor_processing_records',
                'id',
            );
            $table->foreignUuid('avg_processor_processing_record_id')
                ->constrained('avg_processor_processing_records', indexName: $avgProcessorProcessingRecordFk)
                ->cascadeOnDelete();

            $systemFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'systems',
                'id',
            );
            $table->foreignUuid('system_id')
                ->constrained('systems', indexName: $systemFk)
                ->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::create('avg_responsible_processing_record_system', static function (Blueprint $table): void {
            $avgResponsibleProcessingRecordFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'avg_responsible_processing_records',
                'id',
            );
            $table->foreignUuid('avg_responsible_processing_record_id')
                ->constrained('avg_responsible_processing_records', indexName: $avgResponsibleProcessingRecordFk)
                ->cascadeOnDelete();

            $systemFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'systems',
                'id',
            );
            $table->foreignUuid('system_id')
                ->constrained('systems', indexName: $systemFk)
                ->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::create('system_wpg_processing_record', static function (Blueprint $table): void {
            $wpgProcessingRecordFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'wpg_processing_records',
                'id',
            );
            $table->foreignUuid('wpg_processing_record_id')
                ->constrained('wpg_processing_records', indexName: $wpgProcessingRecordFk)
                ->cascadeOnDelete();

            $systemFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'systems',
                'id',
            );
            $table->foreignUuid('system_id')
                ->constrained('systems', indexName: $systemFk)
                ->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::dropIfExists('system_relatables');
    }
};
