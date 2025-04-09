<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('avg_responsible_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('security');
            $table->dropColumn('logic');
            $table->dropColumn('importance_consequences');
            $table->dropColumn('outside_eu_protection_level');

            $table->boolean('has_pseudonymization')->default(false);
            $table->boolean('has_encryption')->default(false);
            $table->text('access_description')->nullable();
        });

        Schema::table('avg_responsible_processing_records', static function (Blueprint $table): void {
            $table->text('logic')->nullable();
            $table->text('importance_consequences')->nullable();
            $table->boolean('outside_eu_protection_level')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('avg_responsible_processing_records', static function (Blueprint $table): void {
            $table->text('security')->nullable();
            $table->dropColumn('logic');
            $table->dropColumn('importance_consequences');
            $table->dropColumn('outside_eu_protection_level');

            $table->dropColumn('has_pseudonymization');
            $table->dropColumn('has_encryption');
            $table->dropColumn('access_description');
        });

        Schema::table('avg_responsible_processing_records', static function (Blueprint $table): void {
            $table->text('logic')->default('');
            $table->text('importance_consequences')->default('');
            $table->text('outside_eu_protection_level')->nullable();
        });
    }
};
