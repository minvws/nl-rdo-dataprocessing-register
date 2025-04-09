<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('addresses', static function (Blueprint $table): void {
            $table->dropColumn('organisation_id');
        });
    }

    public function down(): void
    {
        Schema::table('address', static function (Blueprint $table): void {
            $table->foreignUuid('organisation_id')
                ->constrained('organisations')
                ->cascadeOnDelete();
        });
    }
};
