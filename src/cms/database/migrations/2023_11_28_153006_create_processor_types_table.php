<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('processor_types', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organisation_id')
                ->constrained('organisations')
                ->cascadeOnDelete();

            $table->string('name');

            $table->timestamps();
        });

        Schema::table('processors', static function (Blueprint $table): void {
            $table->foreignUuid('processor_type_id')
                ->nullable()
                ->constrained('processor_types');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('processor_types');
    }
};
