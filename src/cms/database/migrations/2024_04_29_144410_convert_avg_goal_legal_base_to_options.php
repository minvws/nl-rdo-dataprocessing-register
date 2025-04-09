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
            $table->dropColumn('avg_goal_legal_base_id');
            $table->string('avg_goal_legal_base')->nullable();
        });

        Schema::table('stakeholders', static function (Blueprint $table): void {
            $table->text('special_collected_data_explanation')->nullable();
        });
    }
};
