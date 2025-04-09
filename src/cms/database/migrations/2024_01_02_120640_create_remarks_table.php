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
        Schema::create('remarks', static function (Blueprint $table): void {
            $table->uuid('id');

            $table->foreignUuid('user_id')
                ->nullable()
                ->constrained('users', indexName: IndexNameTruncater::foreignKey(
                    $table->getTable(),
                    'users',
                    'id',
                ));

            $table->foreignUuid('organisation_id')
                ->nullable()
                ->constrained('organisations', indexName: IndexNameTruncater::foreignKey(
                    $table->getTable(),
                    'organisations',
                    'id',
                ));

            $table->uuidMorphs('remark_relatable', IndexNameTruncater::index(
                $table->getTable(),
                'remark_relatable_type',
                'remark_relatable_id',
            ));

            $table->text('body');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remarks');
    }
};
