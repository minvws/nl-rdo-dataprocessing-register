<?php

declare(strict_types=1);

use App\Services\SqlExport\IndexNameTruncater;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stakeholders', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organisation_id')
                ->constrained('organisations')
                ->cascadeOnDelete();
            $table->string('import_id')->nullable();

            $table->string('description', 512)->nullable();

            $table->boolean('biometric')->default(false);
            $table->boolean('faith_or_belief')->default(false);
            $table->boolean('genetic')->default(false);
            $table->boolean('health')->default(false);
            $table->boolean('no_special_collected_data')->default(false);
            $table->boolean('political_attitude')->default(false);
            $table->boolean('race_or_ethnicity')->default(false);
            $table->boolean('sexual_life')->default(false);
            $table->boolean('trade_association_membership')->default(false);
            $table->boolean('criminal_law')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('stakeholder_relatables', static function (Blueprint $table): void {
            $stakeholderFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'stakeholders',
                'id',
            );
            $table->foreignUuid('stakeholder_id')
                ->constrained('stakeholders', indexName: $stakeholderFk)
                ->cascadeOnDelete();

            $stakeholderRelatableIx = IndexNameTruncater::index(
                $table->getTable(),
                'stakeholder_relatable_type',
                'stakeholder_relatable_id',
            );
            $table->uuidMorphs('stakeholder_relatable', $stakeholderRelatableIx);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stakeholder_relatables');
        Schema::dropIfExists('stakeholders');
    }
};
