<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->dropRemarksColumn('avg_responsible_processing_records');
        $this->dropRemarksColumn('wpg_processing_records');
    }

    public function dropRemarksColumn(string $table): void
    {
        Schema::table($table, static function (Blueprint $table): void {
            $table->dropColumn('remarks');
        });
    }
};
