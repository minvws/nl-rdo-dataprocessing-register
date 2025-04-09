<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wpg_processing_records', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organisation_id')
                ->constrained('organisations')
                ->cascadeOnDelete();
            $table->foreignUuid('responsible_id')
                ->nullable()
                ->constrained('responsibles')
                ->cascadeOnDelete();
            $table->foreignUuid('contact_person_id')
                ->nullable()
                ->constrained('contact_persons')
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('number');
            $table->string('service');
            $table->text('remarks')->nullable();
            $table->string('import_id')->nullable();

            /** Involved */
            $table->boolean('suspects')->default(false);
            $table->boolean('victims')->default(false);
            $table->boolean('convicts')->default(false);
            $table->boolean('third_parties')->default(false);
            $table->string('third_party_explanation')->nullable();

            /** Security */
            $table->string('pseudonymization');
            $table->string('encryption');
            $table->text('electronic_way');
            $table->text('access');
            $table->text('safety_processors');
            $table->text('safety_responsibles');
            $table->text('measures'); // This is a list of texts
            $table->boolean('security')->default(false);

            /** Transfer */
            $table->boolean('outside_eu')->default(false);
            $table->string('outside_eu_description');
            $table->string('outside_eu_adequate_protection_level');
            $table->string('outside_eu_adequate_protection_level_description');

            /** Decision-Making */
            $table->boolean('decision_making')->default(false);
            $table->string('logic');
            $table->string('consequences');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wpg_processing_records');
    }
};
