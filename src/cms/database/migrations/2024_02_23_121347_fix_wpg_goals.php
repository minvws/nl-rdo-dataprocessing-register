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
        Schema::table('wpg_goals', static function (Blueprint $table): void {
            $table->dropColumn('wpg_processing_record_id');
        });

        Schema::create('wpg_goal_relatables', static function (Blueprint $table): void {
            $avgGoalFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'avg_goals',
                'id',
            );
            $table->foreignUuid('wpg_goal_id')
                ->constrained('wpg_goals', indexName: $avgGoalFk)
                ->cascadeOnDelete();

            $avgGoalRelatableIx = IndexNameTruncater::index(
                $table->getTable(),
                'wpg_goal_relatable_type',
                'wpg_goal_relatable_id',
            );
            $table->uuidMorphs('wpg_goal_relatable', $avgGoalRelatableIx);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wpg_goal_relatables');
    }
};
