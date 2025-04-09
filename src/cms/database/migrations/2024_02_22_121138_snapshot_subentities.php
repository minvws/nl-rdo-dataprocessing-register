<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('related_snapshot_sources', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('snapshot_id')
                ->constrained('snapshots')
                ->cascadeOnDelete();
            $table->uuidMorphs('snapshot_source');
            $table->timestamps();

            $table->unique([
                'snapshot_id',
                'snapshot_source_type',
                'snapshot_source_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('related_snapshot_sources');
    }
};
