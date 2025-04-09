<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stakeholders', static function (Blueprint $table): void {
            $table->integer('sort')->default(0);
            $table->dropColumn('no_special_collected_data');
        });

        Schema::table('stakeholder_data_items', static function (Blueprint $table): void {
            $table->integer('sort')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('stakeholders', static function (Blueprint $table): void {
            $table->boolean('no_special_collected_data')->default(false);
            $table->dropColumn('sort');
        });

        Schema::table('stakeholder_data_items', static function (Blueprint $table): void {
            $table->dropColumn('sort');
        });
    }
};
