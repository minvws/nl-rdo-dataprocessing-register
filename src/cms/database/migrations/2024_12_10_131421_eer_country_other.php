<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addCountryOtherColumn('avg_processor_processing_records');
        $this->addCountryOtherColumn('avg_responsible_processing_records');
    }

    private function addCountryOtherColumn(string $table): void
    {
        Schema::table($table, static function (Blueprint $table): void {
            $table->text('country_other')->nullable();
        });
    }
};
