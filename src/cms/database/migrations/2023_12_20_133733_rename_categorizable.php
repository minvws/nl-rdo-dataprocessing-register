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
        Schema::dropIfExists('categorizables');

        Schema::create('category_relatables', static function (Blueprint $table): void {
            $categoryFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'categories',
                'id',
            );
            $table->foreignUuid('category_id')
                ->constrained('categories', indexName: $categoryFk)
                ->cascadeOnDelete();

            $categoryRelatableIx = IndexNameTruncater::index(
                $table->getTable(),
                'category_relatable_type',
                'category_relatable_id',
            );
            $table->uuidMorphs('category_relatable', $categoryRelatableIx);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_relatables');

        Schema::create('categorizables', static function (Blueprint $table): void {
            $categoryFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'categories',
                'id',
            );
            $table->foreignUuid('category_id')
                ->constrained('categories', indexName: $categoryFk)
                ->cascadeOnDelete();

            $categorizableIx = IndexNameTruncater::index(
                $table->getTable(),
                'categorizable_type',
                'categorizable_id',
            );
            $table->uuidMorphs('categorizable', $categorizableIx);

            $table->timestamps();
        });
    }
};
