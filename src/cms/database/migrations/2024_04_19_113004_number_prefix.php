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
        Schema::create('entity_numbers', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->string('number');

            $table->unique(['type', 'number']);
        });

        Schema::create('entity_number_counters', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->string('prefix');
            $table->integer('number')->default(1);

            $table->unique(['type', 'prefix']);
        });

        Schema::table('organisations', static function (Blueprint $table): void {
            $table->string('register_entity_number_counter_id')->unique()->nullable();
            $table->string('databreach_entity_number_counter_id')->unique()->nullable();
        });

        $this->createEntityNumberIdColumn('algorithm_records');
        $this->createEntityNumberIdColumn('data_breach_records');
        $this->createEntityNumberIdColumn('avg_processor_processing_records');
        $this->createEntityNumberIdColumn('avg_responsible_processing_records');
        $this->createEntityNumberIdColumn('wpg_processing_records');

        $this->createImportField('avg_processor_processing_records');
        $this->createImportField('avg_responsible_processing_records');
        $this->createImportField('wpg_processing_records');
    }

    private function createEntityNumberIdColumn(string $table): void
    {
        Schema::table($table, static function (Blueprint $table): void {
            $table->foreignUuid('entity_number_id')
                ->nullable()
                ->constrained('entity_numbers')
                ->cascadeOnDelete();
        });

        DB::statement(sprintf('ALTER TABLE %s ALTER COLUMN number DROP NOT NULL;', $table));
    }

    private function createImportField(string $table): void
    {
        Schema::table($table, static function (Blueprint $table): void {
            $table->string('import_number')->nullable()->index();
        });
    }
};
