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
            $table->dropColumn('contact_person_id');
        });

        Schema::drop('avg_processor_processing_record_contact_person');
        Schema::drop('avg_responsible_processing_record_contact_person');
        Schema::drop('contact_person_wpg_processing_record');

        Schema::create('contact_person_relatables', static function (Blueprint $table): void {
            $contactPersonFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'contact_persons',
                'id',
            );
            $table->foreignUuid('contact_person_id')
                ->constrained('contact_persons', indexName: $contactPersonFk)
                ->cascadeOnDelete();

            $contactPersonRelatableIx = IndexNameTruncater::index(
                $table->getTable(),
                'contact_person_relatable_type',
                'contact_person_relatable_id',
            );
            $table->uuidMorphs('contact_person_relatable', $contactPersonRelatableIx);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::create('avg_processor_processing_record_contact_person', static function (Blueprint $table): void {
            $avgProcessorProcessingRecordFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'avg_processor_processing_records',
                'id',
            );
            $table->foreignUuid('avg_processor_processing_record_id')
                ->constrained('avg_processor_processing_records', indexName: $avgProcessorProcessingRecordFk)
                ->cascadeOnDelete();

            $contactPersonFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'contact_persons',
                'id',
            );
            $table->foreignUuid('contact_person_id')
                ->constrained('contact_persons', indexName: $contactPersonFk)
                ->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::create('avg_responsible_processing_record_contact_person', static function (Blueprint $table): void {
            $avgResponsibleProcessingRecordFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'avg_responsible_processing_records',
                'id',
            );
            $table->foreignUuid('avg_responsible_processing_record_id')
                ->constrained('avg_responsible_processing_records', indexName: $avgResponsibleProcessingRecordFk)
                ->cascadeOnDelete();

            $contactPersonFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'contact_persons',
                'id',
            );
            $table->foreignUuid('contact_person_id')
                ->constrained('contact_persons', indexName: $contactPersonFk)
                ->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::create('contact_person_wpg_processing_record', static function (Blueprint $table): void {
            $wpgProcessingRecordFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'wpg_processing_records',
                'id',
            );
            $table->foreignUuid('wpg_processing_record_id')
                ->constrained('wpg_processing_records', indexName: $wpgProcessingRecordFk)
                ->cascadeOnDelete();

            $contactPersonFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'contact_persons',
                'id',
            );
            $table->foreignUuid('contact_person_id')
                ->constrained('contact_persons', indexName: $contactPersonFk)
                ->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::table('wpg_processing_records', static function (Blueprint $table): void {
            $contactPersonFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'contact_persons',
                'id',
            );
            $table->foreignUuid('contact_person_id')
                ->nullable()
                ->constrained('contact_persons', indexName: $contactPersonFk)
                ->cascadeOnDelete();
        });

        Schema::dropIfExists('contact_person_relatables');
    }
};
