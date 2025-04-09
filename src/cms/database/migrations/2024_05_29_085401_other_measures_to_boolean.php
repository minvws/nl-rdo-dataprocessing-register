<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->dropOtherMeasures('avg_processor_processing_records');
        $this->dropOtherMeasures('avg_responsible_processing_records');
        $this->dropOtherMeasures('wpg_processing_records');

        $this->recreateOtherMeasures('avg_processor_processing_records');
        $this->recreateOtherMeasures('avg_responsible_processing_records');
        $this->recreateOtherMeasures('wpg_processing_records');
    }

    private function dropOtherMeasures(string $table): void
    {
        Schema::table($table, static function (Blueprint $table): void {
            $table->dropColumn('other_measures');
        });
    }

    private function recreateOtherMeasures(string $table): void
    {
        Schema::table($table, static function (Blueprint $table): void {
            $table->boolean('other_measures')->default(false);
        });
    }
};
