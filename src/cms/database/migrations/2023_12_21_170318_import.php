<?php

declare(strict_types=1);

use App\Services\SqlExport\IndexNameTruncater;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Since these tables are getting a unique constraint, clear them first to prevent errors
        $tablesToTruncate = [
            'avg_processor_processing_records',
            'avg_responsible_processing_records',
            'wpg_processing_records',
            'avg_processors',
            'categories',
            'contact_persons',
            'processors',
            'receivers',
            'responsibles',
            'systems',
            'wpg_goals',
        ];
        foreach ($tablesToTruncate as $table) {
            DB::table($table)->truncate();
        }

        Schema::table('avg_goals', static function (Blueprint $table): void {
            $table->string('import_id')->nullable();

            $table->unique(
                ['organisation_id', 'import_id'],
                IndexNameTruncater::unique($table->getTable(), 'organisation_id', 'import_id'),
            );
        });

        Schema::table('avg_processor_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('outside_eu_protection_level');
        });

        Schema::table('avg_processor_processing_records', static function (Blueprint $table): void {
            $table->boolean('outside_eu_protection_level')->default(false);

            $table->unique(
                ['organisation_id', 'import_id'],
                IndexNameTruncater::unique($table->getTable(), 'organisation_id', 'import_id'),
            );
        });

        Schema::table('avg_processors', static function (Blueprint $table): void {
            $table->string('import_id')->nullable();

            $table->unique(
                ['organisation_id', 'import_id'],
                IndexNameTruncater::unique($table->getTable(), 'organisation_id', 'import_id'),
            );
        });

        Schema::table('avg_responsible_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('import_id');
        });

        Schema::table('avg_responsible_processing_records', static function (Blueprint $table): void {
            $table->string('service')->nullable();
            $table->string('import_id')->nullable()->default(null);

            $table->unique(
                ['organisation_id', 'import_id'],
                IndexNameTruncater::unique($table->getTable(), 'organisation_id', 'import_id'),
            );

            $table->dropColumn('text');
        });

        Schema::table('categories', static function (Blueprint $table): void {
            $table->dropColumn('import_id');
        });

        Schema::table('categories', static function (Blueprint $table): void {
            $table->string('import_id')->nullable()->default(null);

            $table->unique(
                ['organisation_id', 'import_id'],
                IndexNameTruncater::unique($table->getTable(), 'organisation_id', 'import_id'),
            );
        });

        Schema::table('contact_persons', static function (Blueprint $table): void {
            $table->unique(
                ['organisation_id', 'import_id'],
                IndexNameTruncater::unique($table->getTable(), 'organisation_id', 'import_id'),
            );
        });

        Schema::table('processors', static function (Blueprint $table): void {
            $table->unique(
                ['organisation_id', 'import_id'],
                IndexNameTruncater::unique($table->getTable(), 'organisation_id', 'import_id'),
            );
        });

        Schema::table('receivers', static function (Blueprint $table): void {
            $table->dropColumn('name');
            $table->text('description')->nullable();

            $table->unique(
                ['organisation_id', 'import_id'],
                IndexNameTruncater::unique($table->getTable(), 'organisation_id', 'import_id'),
            );
        });

        Schema::table('responsibles', static function (Blueprint $table): void {
            $table->unique(
                ['organisation_id', 'import_id'],
                IndexNameTruncater::unique($table->getTable(), 'organisation_id', 'import_id'),
            );
        });

        Schema::table('systems', static function (Blueprint $table): void {
            $table->dropColumn('name');
            $table->text('description')->nullable();

            $table->unique(
                ['organisation_id', 'import_id'],
                IndexNameTruncater::unique($table->getTable(), 'organisation_id', 'import_id'),
            );
        });

        Schema::table('wpg_goals', static function (Blueprint $table): void {
            $table->unique(
                ['organisation_id', 'import_id'],
                IndexNameTruncater::unique($table->getTable(), 'organisation_id', 'import_id'),
            );
        });

        Schema::table('wpg_processing_records', static function (Blueprint $table): void {
            $table->boolean('none_available')->default(false);
            $table->boolean('no_provisioning')->default(false);
            $table->boolean('no_transfer')->default(false);
            $table->boolean('article_15')->default(false);
            $table->boolean('article_15_a')->default(false);
            $table->boolean('article_16')->default(false);
            $table->boolean('article_17')->default(false);
            $table->boolean('article_17_a')->default(false);
            $table->boolean('article_18')->default(false);
            $table->boolean('article_19')->default(false);
            $table->boolean('article_20')->default(false);
            $table->boolean('article_22')->default(false);
            $table->text('explanation_available')->nullable();
            $table->text('explanation_provisioning')->nullable();
            $table->text('explanation_transfer')->nullable();
            $table->boolean('geb_pia')->default(false);

            $table->unique(
                ['organisation_id', 'import_id'],
                IndexNameTruncater::unique($table->getTable(), 'organisation_id', 'import_id'),
            );
        });
    }

    public function down(): void
    {
        $tablesWithUniqueIndex = [
            'avg_goals',
            'avg_processor_processing_records',
            'avg_processors',
            'avg_responsible_processing_records',
            'categories',
            'contact_persons',
            'processors',
            'receivers',
            'responsibles',
            'systems',
            'wpg_goals',
            'wpg_processing_records',
        ];
        foreach ($tablesWithUniqueIndex as $tableName) {
            Schema::table($tableName, static function (Blueprint $table) use ($tableName): void {
                $table->dropUnique(IndexNameTruncater::unique($tableName, 'organisation_id', 'import_id'));
            });
        }

        Schema::table('avg_goals', static function (Blueprint $table): void {
            $table->dropColumn('import_id');
        });

        Schema::table('avg_processors', static function (Blueprint $table): void {
            $table->dropColumn('import_id');
        });

        Schema::table('avg_responsible_processing_records', static function (Blueprint $table): void {
            $table->text('text')->nullable();
            $table->string('import_id')->change()->nullable()->default(null);

            $table->dropColumn('service');
        });

        Schema::table('categories', static function (Blueprint $table): void {
            $table->string('import_id')->change()->nullable()->default('');
        });

        Schema::table('receivers', static function (Blueprint $table): void {
            $table->string('name')->nullable();
            $table->dropColumn('description');
        });

        Schema::table('systems', static function (Blueprint $table): void {
            $table->string('name')->nullable();
            $table->dropColumn('description');
        });

        Schema::table('wpg_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('none_available');
            $table->dropColumn('no_provisioning');
            $table->dropColumn('no_transfer');
            $table->dropColumn('article_15');
            $table->dropColumn('article_15_a');
            $table->dropColumn('article_16');
            $table->dropColumn('article_17');
            $table->dropColumn('article_17_a');
            $table->dropColumn('article_18');
            $table->dropColumn('article_19');
            $table->dropColumn('article_20');
            $table->dropColumn('article_22');
            $table->dropColumn('explanation_available');
            $table->dropColumn('explanation_provisioning');
            $table->dropColumn('explanation_transfer');
            $table->dropColumn('geb_pia');
        });
    }
};
