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
        Schema::drop('avg_responsible_processing_record_receiver');
        Schema::drop('receiver_wpg_processing_record');

        Schema::create('receiver_relatables', static function (Blueprint $table): void {
            $receiverFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'receivers',
                'id',
            );
            $table->foreignUuid('receiver_id')
                ->constrained('receivers', indexName: $receiverFk)
                ->cascadeOnDelete();

            $receiverRelatableIx = IndexNameTruncater::index(
                $table->getTable(),
                'receiver_relatable_type',
                'receiver_relatable_id',
            );
            $table->uuidMorphs('receiver_relatable', $receiverRelatableIx);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::create('avg_responsible_processing_record_receiver', static function (Blueprint $table): void {
            $avgResponsibleProcessingRecordFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'avg_responsible_processing_records',
                'id',
            );
            $table->foreignUuid('avg_responsible_processing_record_id')
                ->constrained('avg_responsible_processing_records', indexName: $avgResponsibleProcessingRecordFk)
                ->cascadeOnDelete();

            $receiverFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'receivers',
                'id',
            );
            $table->foreignUuid('receiver_id')
                ->constrained('receivers', indexName: $receiverFk)
                ->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::create('receiver_wpg_processing_record', static function (Blueprint $table): void {
            $wpgProcessingRecordFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'wpg_processing_records',
                'id',
            );
            $table->foreignUuid('wpg_processing_record_id')
                ->constrained('wpg_processing_records', indexName: $wpgProcessingRecordFk)
                ->cascadeOnDelete();

            $receiverFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'receivers',
                'id',
            );
            $table->foreignUuid('receiver_id')
                ->constrained('receivers', indexName: $receiverFk)
                ->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::dropIfExists('receiver_relatables');
    }
};
