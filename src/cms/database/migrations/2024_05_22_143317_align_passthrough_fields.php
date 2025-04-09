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
            $table->dropColumn('outside_eu_protection_level');
        });

        Schema::table('avg_processor_processing_records', static function (Blueprint $table): void {
            $table->string('outside_eu_protection_level')->nullable();
            $table->string('outside_eu_description')->nullable();
        });

        Schema::table('avg_responsible_processing_records', static function (Blueprint $table): void {
            $table->string('country')->nullable();
        });
    }
};
