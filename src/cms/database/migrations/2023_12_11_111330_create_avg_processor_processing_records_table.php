<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('avg_processor_processing_records', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organisation_id')->constrained('organisations')->cascadeOnDelete();

            $table->string('name');
            $table->string('number')->unique();
            $table->string('service');
            $table->text('responsibility_distribution');
            $table->text('remarks');

            $table->text('pseudonymization');
            $table->text('encryption');
            $table->text('electronic_way');
            $table->text('access');
            $table->text('safety_processors');
            $table->text('safety_responsibles');
            $table->text('measures');
            $table->boolean('security');

            $table->boolean('outside_eu');
            $table->string('country')->nullable();
            $table->text('outside_eu_protection_level');
            $table->text('outside_eu_protection_level_description');

            $table->boolean('decision_making');
            $table->text('logic');
            $table->text('importance_consequences');

            $table->boolean('geb_pia');
            $table->string('import_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avg_processor_processing_records');
    }
};
