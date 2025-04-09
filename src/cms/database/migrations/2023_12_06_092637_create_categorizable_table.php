<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorizables', static function (Blueprint $table): void {
            $table->foreignUuid('category_id')
                ->constrained('categories')
                ->cascadeOnDelete();

            $table->uuidMorphs('categorizable');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorizables');
    }
};
