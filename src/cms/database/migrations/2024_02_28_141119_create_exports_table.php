<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    public function up(): void
    {
        Schema::create('exports', static function (Blueprint $table): void {
            $table->id();
            $table->timestamp('completed_at')->nullable();
            $table->string('file_disk');
            $table->string('file_name')->nullable();
            $table->string('exporter');
            $table->unsignedInteger('processed_rows')->default(0);
            $table->unsignedInteger('total_rows');
            $table->unsignedInteger('successful_rows')->default(0);
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::table('avg_processor_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('geb_pia');
        });
        Schema::table('avg_responsible_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('outside_eu_protection_level');
            $table->dropColumn('geb_dpia_executed');
            $table->dropColumn('geb_dpia_automated');
            $table->dropColumn('geb_dpia_large_scale_processing');
            $table->dropColumn('geb_dpia_large_scale_monitoring');
            $table->dropColumn('geb_dpia_list_required');
            $table->dropColumn('geb_dpia_criteria_wp248');
            $table->dropColumn('geb_dpia_high_risk_freedoms');
        });
        Schema::table('stakeholder_data_items', static function (Blueprint $table): void {
            $table->dropColumn('is_source_stakeholder');
            $table->dropColumn('is_stakeholder_mandatory');
        });

        Schema::table('avg_processor_processing_records', static function (Blueprint $table): void {
            $table->string('geb_pia')->default('unknown');
        });
        Schema::table('avg_responsible_processing_records', static function (Blueprint $table): void {
            $table->string('outside_eu_protection_level')->default('unknown');
            $table->string('geb_dpia_executed')->default('unknown');
            $table->string('geb_dpia_automated')->default('unknown');
            $table->string('geb_dpia_large_scale_processing')->default('unknown');
            $table->string('geb_dpia_large_scale_monitoring')->default('unknown');
            $table->string('geb_dpia_list_required')->default('unknown');
            $table->string('geb_dpia_criteria_wp248')->default('unknown');
            $table->string('geb_dpia_high_risk_freedoms')->default('unknown');
        });
        Schema::table('stakeholder_data_items', static function (Blueprint $table): void {
            $table->string('is_source_stakeholder')->default('unknown');
            $table->string('is_stakeholder_mandatory')->default('unknown');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exports');
    }
};
