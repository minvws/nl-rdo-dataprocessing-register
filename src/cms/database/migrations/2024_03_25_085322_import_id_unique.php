<?php

declare(strict_types=1);

use App\Services\SqlExport\IndexNameTruncater;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->setUniqueImportId('algorithm_records');
        $this->setUniqueImportId('stakeholders');
        $this->setUniqueImportId('stakeholder_data_items');
    }

    private function setUniqueImportId(string $table): void
    {
        DB::statement(sprintf('UPDATE %s SET import_id = NULL', $table));

        Schema::table($table, static function (Blueprint $table): void {
            $table->unique(
                ['organisation_id', 'import_id'],
                IndexNameTruncater::unique($table->getTable(), 'organisation_id', 'import_id'),
            );
        });
    }
};
