<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public const TABLES = [
        'avg_processor_processing_records',
        'avg_responsible_processing_records',
        'wpg_processing_records',
    ];

    public function up(): void
    {
        foreach (self::TABLES as $tableName) {
            Schema::table($tableName, static function (Blueprint $table): void {
                $table->date('review_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        foreach (self::TABLES as $tableName) {
            Schema::table($tableName, static function (Blueprint $table): void {
                $table->dropColumn(['review_at']);
            });
        }
    }
};
