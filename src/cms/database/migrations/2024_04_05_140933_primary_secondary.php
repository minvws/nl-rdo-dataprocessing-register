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
            $table->string('data_collection_source')->default('primary');
        });
        Schema::table('avg_responsible_processing_records', static function (Blueprint $table): void {
            $table->string('data_collection_source')->default('primary');
        });
        Schema::table('wpg_processing_records', static function (Blueprint $table): void {
            $table->string('data_collection_source')->default('primary');
        });
    }

    public function down(): void
    {
        Schema::table('avg_processor_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('data_collection_source');
        });
        Schema::table('avg_responsible_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('data_collection_source');
        });
        Schema::table('wpg_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('data_collection_source');
        });
    }
};
