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
        DB::statement('ALTER TABLE remarks ALTER body DROP NOT NULL');

        DB::statement("UPDATE avg_processor_processing_records SET review_at='2025-01-01 00:00:00' WHERE review_at IS NULL");
        DB::statement("UPDATE avg_responsible_processing_records SET review_at='2025-01-01 00:00:00' WHERE review_at IS NULL");
        DB::statement("UPDATE wpg_processing_records SET review_at='2025-01-01 00:00:00' WHERE review_at IS NULL");

        DB::statement('ALTER TABLE avg_processor_processing_records ALTER review_at DROP NOT NULL');
        DB::statement('ALTER TABLE avg_responsible_processing_records ALTER review_at DROP NOT NULL');
        DB::statement('ALTER TABLE wpg_processing_records ALTER review_at DROP NOT NULL');

        Schema::table('organisations', static function (Blueprint $table): void {
            $table->integer('review_at_default_in_months')->default(0);
        });
    }
};
