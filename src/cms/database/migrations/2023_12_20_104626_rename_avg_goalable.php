<?php

declare(strict_types=1);

use App\Services\SqlExport\IndexNameTruncater;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('avg_goalables');

        Schema::create('avg_goal_relatables', static function (Blueprint $table): void {
            $avgGoalFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'avg_goals',
                'id',
            );
            $table->foreignUuid('avg_goal_id')
                ->constrained('avg_goals', indexName: $avgGoalFk)
                ->cascadeOnDelete();

            $avgGoalRelatableIx = IndexNameTruncater::index(
                $table->getTable(),
                'avg_goal_relatable_type',
                'avg_goal_relatable_id',
            );
            $table->uuidMorphs('avg_goal_relatable', $avgGoalRelatableIx);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avg_goal_relatables');

        Schema::create('avg_goalables', static function (Blueprint $table): void {
            $avgGoalFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'avg_goals',
                'id',
            );
            $table->foreignUuid('avg_goal_id')
                ->constrained('avg_goals', $avgGoalFk)
                ->cascadeOnDelete();

            $avgGoalableIx = IndexNameTruncater::index(
                $table->getTable(),
                'avg_goalable_type',
                'avg_goalable_id',
            );
            $table->uuidMorphs('avg_goalable', $avgGoalableIx);

            $table->timestamps();
        });
    }
};
