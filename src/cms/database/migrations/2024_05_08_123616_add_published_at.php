<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addPublishedAtColumn('avg_responsible_processing_records');
        $this->addPublishedAtColumn('organisations');
    }

    public function down(): void
    {
        $this->dropPublishedAtColumn('avg_responsible_processing_records');
        $this->dropPublishedAtColumn('organisations');
    }

    private function addPublishedAtColumn(string $table): void
    {
        Schema::table($table, static function (Blueprint $table): void {
            $table->dateTime('published_at')->nullable();
        });
    }

    private function dropPublishedAtColumn(string $table): void
    {
        Schema::table($table, static function (Blueprint $table): void {
            $table->dropColumn('published_at');
        });
    }
};
