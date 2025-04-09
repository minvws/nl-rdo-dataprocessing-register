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
            $table->dropColumn('has_subprocessors');
            $table->boolean('has_processors')->default(false);
            $table->boolean('has_systems')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('avg_processor_processing_records', static function (Blueprint $table): void {
            $table->renameColumn('has_processors', 'has_subprocessors');
            $table->dropColumn('has_systems');
        });
    }
};
