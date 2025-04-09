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
        Schema::create('documents', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('organisation_id')
                ->constrained('organisations')
                ->cascadeOnDelete();

            $table->string('name');
            $table->date('expires_at')->nullable();
            $table->date('notify_at')->nullable();
            $table->text('location')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('document_relatables', static function (Blueprint $table): void {
            $documentFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'documents',
                'id',
            );
            $table->foreignUuid('document_id')
                ->constrained('documents', indexName: $documentFk)
                ->cascadeOnDelete();

            $documentRelatableIx = IndexNameTruncater::index(
                $table->getTable(),
                'document_relatable_type',
                'document_relatable_id',
            );
            $table->uuidMorphs('document_relatable', $documentRelatableIx);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_relatables');
        Schema::dropIfExists('documents');
    }
};
