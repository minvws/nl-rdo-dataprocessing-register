<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('avg_goals', static function (Blueprint $table): void {
            $table->text('remarks')->nullable();
            $table->integer('sort')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('avg_goals', static function (Blueprint $table): void {
            $table->dropColumn('remarks');
            $table->dropColumn('sort');
        });
    }
};
