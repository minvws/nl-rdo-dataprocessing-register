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
        Schema::create('stakeholder_data_items', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organisation_id')
                ->constrained('organisations')
                ->cascadeOnDelete();

            $table->string('import_id')->nullable();
            $table->string('description', 512)->nullable();
            $table->string('collection_purpose', 512)->nullable();
            $table->string('retention_period', 512)->nullable();
            $table->string('is_source_stakeholder')->default('onbekend');
            $table->string('source_description', 512)->nullable();
            $table->string('is_stakeholder_mandatory')->default('onbekend');
            $table->string('stakeholder_consequences', 512)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('stakeholder_stakeholder_data_item', static function (Blueprint $table): void {
            $stakeholderDataItemFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'stakeholder_data_items',
                'id',
            );
            $table->foreignUuid('stakeholder_data_item_id')
                ->constrained('stakeholder_data_items', indexName: $stakeholderDataItemFk)
                ->cascadeOnDelete();

            $stakeholderFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'stakeholders',
                'id',
            );
            $table->foreignUuid('stakeholder_id')
                ->constrained('stakeholders', indexName: $stakeholderFk)
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stakeholder_stakeholder_data_item');
        Schema::dropIfExists('stakeholder_data_items');
    }
};
