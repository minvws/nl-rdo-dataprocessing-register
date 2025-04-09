<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avg_responsible_processing_record_responsible', static function (Blueprint $table): void {
            $table->foreignUuid('avg_responsible_processing_record_id')
                ->constrained('avg_responsible_processing_records')
                ->cascadeOnDelete();
            $table->foreignUuid('responsible_id')
                ->constrained('responsibles')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avg_responsible_processing_record_responsible');
    }
};
