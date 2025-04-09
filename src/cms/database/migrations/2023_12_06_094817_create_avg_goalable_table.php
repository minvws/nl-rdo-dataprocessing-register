<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('avg_goalables', static function (Blueprint $table): void {
            $table->foreignUuid('avg_goal_id')->constrained('avg_goals')->cascadeOnDelete();
            $table->uuidMorphs('avg_goalable');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avg_goalables');
    }
};
