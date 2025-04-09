<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->setDefaultValueToUnknown('avg_processor_processing_records', 'has_arrangements_with_responsibles');
        $this->setDefaultValueToUnknown('avg_processor_processing_records', 'has_arrangements_with_processors');
    }

    private function setDefaultValueToUnknown(string $table, string $field): void
    {
        DB::statement(sprintf("ALTER TABLE %s ALTER COLUMN %s SET DEFAULT 'unknown';", $table, $field));
    }
};
