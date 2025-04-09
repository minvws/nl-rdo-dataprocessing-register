<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->dropNumberColumn('algorithm_records');
        $this->dropNumberColumn('avg_processor_processing_records');
        $this->dropNumberColumn('avg_responsible_processing_records');
        $this->dropNumberColumn('wpg_processing_records');

        $this->setOrganisationColumnNotNull('databreach_entity_number_counter_id');
        $this->setOrganisationColumnNotNull('register_entity_number_counter_id');
    }

    private function dropNumberColumn(string $table): void
    {
        Schema::table($table, static function (Blueprint $table): void {
            $table->dropColumn('number');
        });
    }

    private function setOrganisationColumnNotNull(string $column): void
    {
        DB::statement(sprintf('ALTER TABLE organisations ALTER COLUMN %s SET NOT NULL;', $column));
    }
};
