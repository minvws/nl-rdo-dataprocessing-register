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
        Schema::table('avg_responsible_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('responsibility_distribution');
            $table->dropColumn('pseudonymization');
            $table->dropColumn('encryption');
            $table->dropColumn('electronic_way');
            $table->dropColumn('access');
            $table->dropColumn('safety_processors');
            $table->dropColumn('safety_responsibles');
            $table->dropColumn('measures');
            $table->dropColumn('security');
            $table->dropColumn('outside_eu_description');
            $table->dropColumn('outside_eu_protection_level');
            $table->dropColumn('outside_eu_protection_level_description');

            $table->boolean('has_processors')->default(false);
            $table->boolean('has_security')->default(false);
            $table->boolean('has_systems')->default(false);
        });

        Schema::table('avg_responsible_processing_records', static function (Blueprint $table): void {
            $table->text('responsibility_distribution')->nullable();
            $table->text('pseudonymization')->nullable();
            $table->text('encryption')->nullable();
            $table->text('electronic_way')->nullable();
            $table->text('access')->nullable();
            $table->text('safety_processors')->nullable();
            $table->text('safety_responsibles')->nullable();
            $table->text('measures')->nullable();
            $table->text('security')->nullable();
            $table->text('outside_eu_description')->nullable();
            $table->text('outside_eu_protection_level')->nullable();
            $table->text('outside_eu_protection_level_description')->nullable();
        });

        Schema::table('wpg_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('explanation_available');
            $table->dropColumn('explanation_provisioning');
            $table->dropColumn('explanation_transfer');
            $table->dropColumn('logic');
            $table->dropColumn('consequences');
            $table->dropColumn('pseudonymization');
            $table->dropColumn('encryption');
            $table->dropColumn('electronic_way');
            $table->dropColumn('access');
            $table->dropColumn('safety_processors');
            $table->dropColumn('safety_responsibles');
            $table->dropColumn('measures');
            $table->dropColumn('outside_eu_description');
            $table->dropColumn('outside_eu_adequate_protection_level');
            $table->dropColumn('outside_eu_adequate_protection_level_description');
        });

        Schema::table('wpg_processing_records', static function (Blueprint $table): void {
            $table->text('explanation_available')->nullable();
            $table->text('explanation_provisioning')->nullable();
            $table->text('explanation_transfer')->nullable();
            $table->text('logic')->nullable();
            $table->text('consequences')->nullable();
            $table->text('pseudonymization')->nullable();
            $table->text('encryption')->nullable();
            $table->text('electronic_way')->nullable();
            $table->text('access')->nullable();
            $table->text('safety_processors')->nullable();
            $table->text('safety_responsibles')->nullable();
            $table->text('measures')->nullable();
            $table->text('outside_eu_description')->nullable();
            $table->text('outside_eu_adequate_protection_level')->nullable();
            $table->text('outside_eu_adequate_protection_level_description')->nullable();
        });

        DB::table('snapshots')
            ->where(['state' => 'published'])
            ->update(['state' => 'established']);
        DB::table('snapshot_transitions')
            ->where(['state' => 'published'])
            ->update(['state' => 'established']);
    }

    public function down(): void
    {
        Schema::table('avg_responsible_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('has_processors');
            $table->dropColumn('has_security');
            $table->dropColumn('has_systems');
        });

        DB::table('snapshots')
            ->where(['state' => 'established'])
            ->update(['state' => 'published']);
        DB::table('snapshot_transitions')
            ->where(['state' => 'established'])
            ->update(['state' => 'published']);
    }
};
