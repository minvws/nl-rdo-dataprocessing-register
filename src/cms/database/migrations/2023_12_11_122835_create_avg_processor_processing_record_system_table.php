<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('avg_processor_processing_record_system', static function (Blueprint $table): void {
            $table->foreignUuid('avg_processor_processing_record_id')->constrained(
                'avg_processor_processing_records',
            )->cascadeOnDelete();
            $table->foreignUuid('system_id')->constrained('systems')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avg_processor_processing_record_system');
    }
};
