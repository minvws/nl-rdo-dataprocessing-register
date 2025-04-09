<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('snapshot_data', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('snapshot_id')
                ->constrained('snapshots');
            $table->unique('snapshot_id');
            $table->text('public_frontmatter')->nullable();
            $table->text('public_markdown')->nullable();
            $table->text('private_frontmatter');
            $table->text('private_markdown');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('snapshot_data');
    }
};
