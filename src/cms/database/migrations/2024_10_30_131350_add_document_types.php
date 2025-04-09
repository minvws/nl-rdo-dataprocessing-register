<?php

declare(strict_types=1);

use App\Services\SqlExport\IndexNameTruncater;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('document_types', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organisation_id')
                ->constrained('organisations', indexName: IndexNameTruncater::foreignKey(
                    $table->getTable(),
                    'organisations',
                    'id',
                ))
                ->cascadeOnDelete();

            $table->text('name')->index();
            $table->boolean('enabled')->default(true);

            $table->timestamps();
        });

        Schema::table('documents', static function (Blueprint $table): void {
            $table->foreignUuid('document_type_id')
                ->nullable()
                ->constrained('document_types', indexName: IndexNameTruncater::foreignKey(
                    $table->getTable(),
                    'document_types',
                    'id',
                ));
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_types');
    }
};
