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
        $this->createSoftDeleteColumn('algorithm_meta_schemas');
        $this->createSoftDeleteColumn('algorithm_publication_categories');
        $this->createSoftDeleteColumn('algorithm_records');
        $this->createSoftDeleteColumn('algorithm_statuses');
        $this->createSoftDeleteColumn('algorithm_themes');

        $this->createSoftDeleteColumn('avg_goals');
        $this->createNewUniqueImport('avg_goals', 'avg_goals_organisa_id_import_id_unique');
        $this->createSoftDeleteColumn('avg_goal_legal_bases');
        $this->createSoftDeleteColumn('avg_processors');
        $this->createNewUniqueImport('avg_processors', 'avg_processo_organisa_id_import_id_unique');
        $this->createSoftDeleteColumn('avg_processor_processing_records');
        $this->createNewUniqueImport('avg_processor_processing_records', 'avg_pro_pro_rec_organisa_id_import_id_unique');
        $this->createNewUniqueNumber('avg_processor_processing_records', 'avg_processor_processing_records_number_unique');
        $this->createSoftDeleteColumn('avg_processor_processing_record_service');
        $this->createSoftDeleteColumn('avg_responsible_processing_records');
        $this->createNewUniqueImport('avg_responsible_processing_records', 'avg_res_pro_rec_organisa_id_import_id_unique');
        $this->createNewUniqueNumber('avg_responsible_processing_records', 'avg_responsible_processing_records_number_unique');
        $this->createSoftDeleteColumn('avg_responsible_processing_record_service');

        $this->createSoftDeleteColumn('wpg_goals');
        $this->createNewUniqueImport('wpg_goals', 'wpg_goals_organisa_id_import_id_unique');
        $this->createSoftDeleteColumn('wpg_processing_records');
        $this->createNewUniqueImport('wpg_processing_records', 'wpg_proce_recor_organisa_id_import_id_unique');
        $this->createNewUniqueNumber('wpg_processing_records', 'wpg_processi_records_number_unique');
        $this->createSoftDeleteColumn('wpg_processing_record_service');

        $this->createSoftDeleteColumn('addresses');
        $this->createSoftDeleteColumn('categories');
        $this->createNewUniqueImport('categories', 'categories_organisa_id_import_id_unique');
        $this->createSoftDeleteColumn('contact_persons');
        $this->createNewUniqueImport('contact_persons', 'contact_persons_organisa_id_import_id_unique');
        $this->createSoftDeleteColumn('contact_person_positions');
        $this->createSoftDeleteColumn('domains');
        $this->createSoftDeleteColumn('organisations');
        $this->createSoftDeleteColumn('processors');
        $this->createNewUniqueImport('processors', 'processors_organisa_id_import_id_unique');
        $this->createSoftDeleteColumn('processor_types');
        $this->createSoftDeleteColumn('receivers');
        $this->createNewUniqueImport('receivers', 'receivers_organisa_id_import_id_unique');
        $this->createSoftDeleteColumn('remarks');
        $this->createSoftDeleteColumn('responsibles');
        $this->createNewUniqueImport('responsibles', 'responsibles_organisa_id_import_id_unique');
        $this->createSoftDeleteColumn('responsible_types');
        $this->createSoftDeleteColumn('systems');
        $this->createNewUniqueImport('systems', 'systems_organisa_id_import_id_unique');
        $this->createSoftDeleteColumn('tags');
        $this->createSoftDeleteColumn('users');

        DB::statement("CREATE UNIQUE INDEX algorithm_records_number ON algorithm_records (number) WHERE deleted_at IS NULL;");

        Schema::table('avg_processor_processing_records', static function (Blueprint $table): void {
            $table->dropColumn('geb_pia');
        });

        Schema::table('avg_processor_processing_records', static function (Blueprint $table): void {
            $table->text('geb_pia')->nullable();
        });
    }

    private function createSoftDeleteColumn(string $tableName): void
    {
        Schema::table($tableName, static function (Blueprint $table): void {
            $table->softDeletes('deleted_at');
        });
    }

    private function createNewUniqueImport(string $tableName, string $currentUnique): void
    {
        Schema::table($tableName, static function (Blueprint $table) use ($currentUnique): void {
            $table->dropUnique($currentUnique);
        });

        DB::statement(sprintf(
            "CREATE UNIQUE INDEX %s_organisation_import ON %s (organisation_id, import_id) WHERE deleted_at IS NULL;",
            $tableName,
            $tableName,
        ));
    }

    private function createNewUniqueNumber(string $tableName, string $currentUnique): void
    {
        Schema::table($tableName, static function (Blueprint $table) use ($currentUnique): void {
            $table->dropUnique($currentUnique);
        });

        DB::statement(sprintf(
            "CREATE UNIQUE INDEX %s_number ON %s (number) WHERE deleted_at IS NULL;",
            $tableName,
            $tableName,
        ));
    }
};
