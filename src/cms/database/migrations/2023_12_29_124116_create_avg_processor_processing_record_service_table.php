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
        Schema::create('avg_processor_processing_record_service', static function (Blueprint $table): void {
            $table->uuid('id')->primary();

            $organisationFk = IndexNameTruncater::foreignKey($table->getTable(), 'organisations', 'id');
            $table->foreignUuid('organisation_id')
                ->constrained('organisations', indexName: $organisationFk)
                ->cascadeOnDelete();

            $table->text('name')->index();
            $table->boolean('enabled');

            $table->timestamps();
        });

        Schema::table('avg_processor_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('service');

            $avgProcessorProcessingRecordServiceFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'avg_processor_processing_record_service',
                'id',
            );
            $table->foreignUuid('avg_processor_processing_record_service_id')
                ->nullable()
                ->constrained('avg_processor_processing_record_service', indexName: $avgProcessorProcessingRecordServiceFk);
        });
    }

    public function down(): void
    {
        Schema::table('avg_processor_processing_records', static function (Blueprint $table): void {
            $table->string('service')->nullable();

            $avgProcessorProcessingRecordServiceFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'avg_processor_processing_record_service',
                'id',
            );
            $table->dropForeign($avgProcessorProcessingRecordServiceFk);

            $table->dropColumn('avg_processor_processing_record_service_id');
        });

        Schema::dropIfExists('avg_processor_processing_record_service');
    }
};
