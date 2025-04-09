<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::drop('avg_processor_processing_record_avg_processor');
        Schema::drop('avg_processors');

        Schema::table('avg_responsible_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('outside_eu_protection_level');
            $table->dropColumn('dpia');
        });

        Schema::table('avg_responsible_processing_records', static function (Blueprint $table): void {
            $table->string('outside_eu_protection_level')->default('onbekend');
            $table->string('geb_dpia_executed')->default('onbekend');
            $table->string('geb_dpia_automated')->default('onbekend');
            $table->string('geb_dpia_large_scale_processing')->default('onbekend');
            $table->string('geb_dpia_large_scale_monitoring')->default('onbekend');
            $table->string('geb_dpia_list_required')->default('onbekend');
            $table->string('geb_dpia_criteria_wp248')->default('onbekend');
            $table->string('geb_dpia_high_risk_freedoms')->default('onbekend');
        });
    }
};
