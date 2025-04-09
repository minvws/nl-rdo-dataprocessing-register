<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE avg_processor_processing_records ALTER COLUMN direct_access SET DATA TYPE TEXT');
        DB::statement('ALTER TABLE avg_processor_processing_records ALTER COLUMN electronic_way SET DATA TYPE TEXT');
        DB::statement('ALTER TABLE avg_processor_processing_records ALTER COLUMN measures SET DATA TYPE TEXT');

        Schema::create('data_breach_records', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organisation_id')
                ->constrained('organisations')
                ->cascadeOnDelete();

            $table->string('number')->index();
            $table->string('name')->index();
            $table->string('type');
            $table->date('reported_at')->nullable();
            $table->boolean('ap_reported');

            $table->date('discovered_at')->nullable();
            $table->date('started_at')->nullable();
            $table->date('ended_at')->nullable();
            $table->date('ap_reported_at')->nullable();
            $table->date('completed_at')->nullable();

            $table->text('nature_of_incident')->nullable();
            $table->text('nature_of_incident_other')->nullable();
            $table->text('summary')->nullable();
            $table->text('involved_people')->nullable();
            $table->text('personal_data_categories')->nullable();
            $table->text('personal_data_categories_other')->nullable();
            $table->text('personal_data_special_categories')->nullable();
            $table->text('estimated_risk')->nullable();
            $table->text('measures')->nullable();
            $table->boolean('reported_to_involved');
            $table->text('reported_to_involved_communication')->nullable();
            $table->text('reported_to_involved_communication_other')->nullable();
            $table->boolean('fg_reported');

            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement("CREATE UNIQUE INDEX data_breach_records_number ON data_breach_records (number) WHERE deleted_at IS NULL;");

        Schema::create('data_breach_record_relatables', static function (Blueprint $table): void {
            $table->foreignUuid('data_breach_record_id')
                ->constrained('data_breach_records')
                ->cascadeOnDelete();

            $table->uuidMorphs('data_breach_record_relatable');
            $table->unique(['data_breach_record_id', 'data_breach_record_relatable_id', 'data_breach_record_relatable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_breach_recod_relatables');
        Schema::dropIfExists('data_breach_records');
    }
};
