<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->convertYesNoUnknownColumnToBoolean('stakeholder_data_items', 'is_source_stakeholder');

        $this->convertYesNoUnknownColumnToBoolean('avg_processor_processing_records', 'geb_pia');
        $this->convertYesNoUnknownColumnToBoolean('avg_processor_processing_records', 'outside_eu_protection_level');

        $this->convertYesNoUnknownColumnToBoolean('avg_responsible_processing_records', 'geb_dpia_executed');
        $this->convertYesNoUnknownColumnToBoolean('avg_responsible_processing_records', 'geb_dpia_automated');
        $this->convertYesNoUnknownColumnToBoolean('avg_responsible_processing_records', 'geb_dpia_large_scale_processing');
        $this->convertYesNoUnknownColumnToBoolean('avg_responsible_processing_records', 'geb_dpia_large_scale_monitoring');
        $this->convertYesNoUnknownColumnToBoolean('avg_responsible_processing_records', 'geb_dpia_list_required');
        $this->convertYesNoUnknownColumnToBoolean('avg_responsible_processing_records', 'geb_dpia_criteria_wp248');
        $this->convertYesNoUnknownColumnToBoolean('avg_responsible_processing_records', 'geb_dpia_high_risk_freedoms');
        $this->convertYesNoUnknownColumnToBoolean('avg_responsible_processing_records', 'outside_eu_protection_level');
    }

    private function convertYesNoUnknownColumnToBoolean(string $table, string $column): void
    {
        DB::statement(sprintf("ALTER TABLE %s ALTER COLUMN %s DROP DEFAULT", $table, $column));
        DB::statement(
            sprintf("ALTER TABLE %s ALTER %s TYPE bool USING CASE WHEN %s='yes' THEN TRUE ELSE FALSE END", $table, $column, $column),
        );
        DB::statement(sprintf("ALTER TABLE %s ALTER COLUMN %s SET DEFAULT false", $table, $column));
    }
};
