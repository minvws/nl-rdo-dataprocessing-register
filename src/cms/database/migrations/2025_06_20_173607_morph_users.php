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
        Schema::create('user_relatables', static function (Blueprint $table): void {
            $userFk = IndexNameTruncater::foreignKey(
                $table->getTable(),
                'users',
                'id',
            );
            $table->foreignUuid('user_id')
                ->constrained('users', indexName: $userFk)
                ->cascadeOnDelete();

            $userRelatableIx = IndexNameTruncater::index(
                $table->getTable(),
                'user_relatable_type',
                'user_relatable_id',
            );
            $table->uuidMorphs('user_relatable', $userRelatableIx);

            $table->timestamps();
        });
    }
};
