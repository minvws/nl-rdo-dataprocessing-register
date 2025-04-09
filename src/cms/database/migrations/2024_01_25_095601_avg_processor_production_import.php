<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('avg_processor_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('measures_description');
        });

        Schema::table('avg_processor_processing_records', static function (Blueprint $table): void {
            $table->text('measures_description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('avg_processor_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('measures_description');
        });

        Schema::table('avg_processor_processing_records', static function (Blueprint $table): void {
            $table->text('measures_description');
        });
    }
};
