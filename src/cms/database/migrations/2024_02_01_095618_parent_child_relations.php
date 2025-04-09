<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('algorithm_records', static function (Blueprint $table): void {
            $table->foreignUuid('parent_id')
                ->nullable()
                ->constrained('algorithm_records');
        });
        Schema::table('avg_processor_processing_records', static function (Blueprint $table): void {
            $table->foreignUuid('parent_id')
                ->nullable()
                ->constrained('avg_processor_processing_records');
        });
        Schema::table('avg_responsible_processing_records', static function (Blueprint $table): void {
            $table->foreignUuid('parent_id')
                ->nullable()
                ->constrained('avg_responsible_processing_records');
        });
        Schema::table('wpg_processing_records', static function (Blueprint $table): void {
            $table->foreignUuid('parent_id')
                ->nullable()
                ->constrained('wpg_processing_records');
        });
    }

    public function down(): void
    {
        Schema::table('algorithm_records', static function (Blueprint $table): void {
            $table->dropColumn('parent_id');
        });
        Schema::table('avg_processor_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('parent_id');
        });
        Schema::table('avg_responsible_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('parent_id');
        });
        Schema::table('wpg_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('parent_id');
        });
    }
};
