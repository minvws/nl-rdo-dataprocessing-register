<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('avg_processor_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('measures');
            $table->dropColumn('has_arrangements_with_responsibles');
            $table->dropColumn('arrangements_with_responsibles_description');
            $table->dropColumn('has_arrangements_with_processors');
            $table->dropColumn('arrangements_with_processors_description');
            $table->dropColumn('direct_access');
            $table->dropColumn('direct_access_third_party_description');
            $table->dropColumn('electronic_way');
            $table->dropColumn('has_encryption');
            $table->dropColumn('encryption');

            $table->boolean('measures_implemented')->default(false);
            $table->text('other_measures')->nullable();
        });
        Schema::table('avg_responsible_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('measures');
            $table->dropColumn('safety_processors');
            $table->dropColumn('access');
            $table->dropColumn('access_description');
            $table->dropColumn('electronic_way');
            $table->dropColumn('has_encryption');
            $table->dropColumn('safety_responsibles');
            $table->dropColumn('encryption');

            $table->boolean('measures_implemented')->default(false);
            $table->text('other_measures')->nullable();
        });
        Schema::table('wpg_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('measures');
            $table->dropColumn('safety_processors');
            $table->dropColumn('access');
            $table->dropColumn('access_description');
            $table->dropColumn('electronic_way');
            $table->dropColumn('has_encryption');
            $table->dropColumn('encryption');

            $table->boolean('measures_implemented')->default(false);
            $table->text('other_measures')->nullable();
        });
    }
};
