<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('avg_goals', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organisation_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->text('goal');
            $table->string('legal_basis');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avg_goals');
    }
};
