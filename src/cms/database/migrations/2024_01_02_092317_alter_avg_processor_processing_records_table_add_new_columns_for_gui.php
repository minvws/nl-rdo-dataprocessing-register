<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE
                avg_processor_processing_records ALTER responsibility_distribution
            SET
                DEFAULT ''
        ");
        DB::statement("ALTER TABLE
                avg_processor_processing_records ALTER pseudonymization
            SET
                DEFAULT ''
        ");

        DB::statement("ALTER TABLE
                avg_processor_processing_records ALTER encryption
            SET
                DEFAULT ''
        ");

        Schema::table('avg_processor_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('encryption');
            $table->dropColumn('pseudonymization');
        });

        Schema::table('avg_processor_processing_records', static function (Blueprint $table): void {
            $table->boolean('has_subprocessors')->default(false);
            $table->boolean('has_arrangements_with_responsibles')->default(false);
            $table->boolean('has_arrangements_with_processors')->default(false);

            //Add booleans to hide parts of the wizard
            $table->boolean('has_pseudonymization')->default(false);
            $table->boolean('has_encryption')->default(false);
            $table->boolean('has_goal')->default(false);

            //Add columns for the involved parties
            $table->boolean('has_involved')->default(false);
            $table->boolean('suspects')->default(false);
            $table->boolean('victims')->default(false);
            $table->boolean('convicts')->default(false);
            $table->boolean('third_parties')->default(false);
            $table->text('third_parties_description')->default(false);

            //Set defaults for non-nullable fields
            $table->text('encryption')->default('');
            $table->text('pseudonymization')->default('');

            //Remove columns that are being renamed or are no longer needed
            $table->dropColumn('security');
            $table->dropColumn('safety_responsibles');
            $table->dropColumn('safety_processors');
            $table->dropColumn('access');
            $table->dropColumn('electronic_way');
            $table->dropColumn('measures');
            $table->dropColumn('remarks');
        });

        Schema::table('avg_processor_processing_records', static function (Blueprint $table): void {
            $table->boolean('has_security')->default(false);
            $table->text('arrangements_with_responsibles_description')->nullable()->default('');
            $table->text('arrangements_with_processors_description')->nullable()->default('');

            $table->json('direct_access')->default('[]');
            $table->text('direct_access_third_party_description')->default('');

            $table->text('measures_description')->default('');

            $table->json('electronic_way')->default('[]');
            $table->json('measures')->default('[]');

            //Add default values for nullable fields
            DB::statement("ALTER TABLE
                    avg_processor_processing_records ALTER outside_eu_protection_level_description
                SET
                    DEFAULT ''
            ");
            DB::statement("ALTER TABLE
                    avg_processor_processing_records ALTER logic
                SET
                    DEFAULT ''
            ");
            DB::statement("ALTER TABLE
                    avg_processor_processing_records ALTER importance_consequences
                SET
                    DEFAULT ''
            ");
            DB::statement("ALTER TABLE
                    avg_processor_processing_records
                ALTER
                    geb_pia TYPE TEXT
            ");
            DB::statement("ALTER TABLE
                    avg_processor_processing_records ALTER geb_pia
                SET
                    DEFAULT ''
            ");
        });
    }

    public function down(): void
    {
        Schema::table('avg_processor_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('has_security');
            $table->dropColumn('arrangements_with_responsibles_description');
            $table->dropColumn('arrangements_with_processors_description');
            $table->dropColumn('direct_access');
            $table->dropColumn('direct_access_third_party_description');
            $table->dropColumn('measures_description');
            $table->dropColumn('electronic_way');
            $table->dropColumn('measures');

            $table->dropColumn('has_involved');
            $table->dropColumn('suspects');
            $table->dropColumn('victims');
            $table->dropColumn('convicts');
            $table->dropColumn('third_parties');
            $table->dropColumn('third_parties_description');

            $table->dropColumn('has_pseudonymization');
            $table->dropColumn('has_encryption');
            $table->dropColumn('has_goal');

            $table->dropColumn('has_subprocessors');
            $table->dropColumn('has_arrangements_with_responsibles');
            $table->dropColumn('has_arrangements_with_processors');
        });

        Schema::table('avg_processor_processing_records', static function (Blueprint $table): void {
            $table->boolean('security')->default(false);
            $table->text('safety_responsibles')->nullable();
            $table->text('safety_processors')->nullable();
            $table->text('access')->nullable();
            $table->text('electronic_way')->nullable();
            $table->text('measures')->nullable();
            $table->text('remarks')->nullable();
        });
    }
};
