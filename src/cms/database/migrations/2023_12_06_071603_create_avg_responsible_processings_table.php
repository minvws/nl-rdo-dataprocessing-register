<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avg_responsible_processing_records', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organisation_id')
                ->constrained('organisations')
                ->cascadeOnDelete();

            $table->string('name');
            $table->text('text');
            $table->string('number')->unique();
            $table->text('responsibility_distribution')->default('');
            $table->text('remarks')->default('');
            $table->text('pseudonymization');
            $table->text('encryption');
            $table->text('electronic_way'); //lists
            $table->text('access'); //lists
            $table->text('safety_processors');
            $table->text('safety_responsibles');
            $table->text('measures'); // This is a list of texts
            $table->boolean('security')->default(false);
            $table->boolean('outside_eu')->default(false);
            $table->text('outside_eu_description')->default('');
            $table->text('outside_eu_protection_level')->default('');
            $table->text('outside_eu_protection_level_description')->default('');
            $table->boolean('decision_making')->default(false);
            $table->text('logic')->default('');
            $table->text('importance_consequences')->default('');
            $table->boolean('dpia')->default(false);
            $table->string('import_id')->default('');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avg_responsible_processing_records');
    }
};
