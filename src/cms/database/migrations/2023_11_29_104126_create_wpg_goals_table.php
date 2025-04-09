<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wpg_goals', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organisation_id')
                ->constrained('organisations')
                ->cascadeOnDelete();
            $table->foreignUuid('wpg_processing_record_id')
                ->constrained('wpg_processing_records')
                ->cascadeOnDelete();

            $table->string('description');
            $table->boolean('article_8')->default(false);
            $table->boolean('article_9')->default(false);
            $table->boolean('article_10_1a')->default(false);
            $table->boolean('article_10_1b')->default(false);
            $table->boolean('article_10_1c')->default(false);
            $table->boolean('article_12')->default(false);
            $table->boolean('article_13_1')->default(false);
            $table->boolean('article_13_2')->default(false);
            $table->boolean('article_13_3')->default(false);
            $table->text('explanation')->nullable();
            $table->string('import_id')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wpg_goals');
    }
};
