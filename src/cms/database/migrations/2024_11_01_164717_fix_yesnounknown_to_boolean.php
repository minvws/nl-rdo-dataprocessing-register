<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->convertYesNoUnknownColumnToBoolean('stakeholder_data_items', 'is_stakeholder_mandatory');
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
