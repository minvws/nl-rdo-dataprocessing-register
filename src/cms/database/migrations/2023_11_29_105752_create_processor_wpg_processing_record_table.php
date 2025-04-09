<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('processor_wpg_processing_record', static function (Blueprint $table): void {
            $table->foreignUuid('processor_id')
                ->constrained('processors')
                ->cascadeOnDelete();
            $table->foreignUuid('wpg_processing_record_id')
                ->constrained('wpg_processing_records')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('processor_wpg_processing_record');
    }
};
