<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wpg_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('security');
            $table->dropColumn('safety_responsibles');
            $table->dropColumn('outside_eu');
            $table->dropColumn('outside_eu_description');
            $table->dropColumn('outside_eu_adequate_protection_level');
            $table->dropColumn('outside_eu_adequate_protection_level_description');

            $table->boolean('has_processors')->default(false);
            $table->boolean('has_systems')->default(false);
            $table->boolean('has_security')->default(false);
            $table->boolean('has_encryption')->default(false);
            $table->boolean('has_pseudonymization')->default(false);

            $table->boolean('article_23')->default(false);
            $table->boolean('article_24')->default(false);

            $table->boolean('police_none')->default(false);
            $table->boolean('police_race_or_ethnicity')->default(false);
            $table->boolean('police_political_attitude')->default(false);
            $table->boolean('police_faith_or_belief')->default(false);
            $table->boolean('police_association_membership')->default(false);
            $table->boolean('police_genetic')->default(false);
            $table->boolean('police_identification')->default(false);
            $table->boolean('police_health')->default(false);
            $table->boolean('police_sexual_life')->default(false);

            $table->boolean('police_justice')->default(false);

            $table->text('measures_description')->nullable();
            $table->text('access_description')->nullable();
        });

        DB::statement('ALTER TABLE wpg_processing_records ALTER COLUMN third_party_explanation SET DATA TYPE text');

        Schema::table('wpg_goals', static function (Blueprint $table): void {
            $table->text('remarks')->nullable();
            $table->integer('sort')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('wpg_processing_records', static function (Blueprint $table): void {
            $table->boolean('security')->default(false);

            $table->dropColumn('has_processors');
            $table->dropColumn('has_systems');
            $table->dropColumn('has_security');
            $table->dropColumn('has_encryption');
            $table->dropColumn('has_pseudonymization');

            $table->dropColumn('article_23');
            $table->dropColumn('article_24');

            $table->dropColumn('police_none');
            $table->dropColumn('police_race_or_ethnicity');
            $table->dropColumn('police_political_attitude');
            $table->dropColumn('police_faith_or_belief');
            $table->dropColumn('police_association_membership');
            $table->dropColumn('police_genetic');
            $table->dropColumn('police_identification');
            $table->dropColumn('police_health');
            $table->dropColumn('police_sexual_life');

            $table->dropColumn('police_justice');

            $table->dropColumn('measures_description');
            $table->dropColumn('access_description');
        });

        Schema::table('wpg_goals', static function (Blueprint $table): void {
            $table->dropColumn('remarks');
            $table->dropColumn('sort');
        });
    }
};
