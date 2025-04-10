<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organisations', static function (Blueprint $table): void {
            $table->dateTime('public_from')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('organisations', static function (Blueprint $table): void {
            $table->dropColumn('public_from');
        });
    }
};
