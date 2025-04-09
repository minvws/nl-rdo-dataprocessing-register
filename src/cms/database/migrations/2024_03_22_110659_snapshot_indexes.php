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
        Schema::table('snapshots', static function (Blueprint $table): void {
            $table->index('name');
        });

        DB::statement('DELETE FROM snapshots WHERE snapshot_source_type IS NULL');
        DB::statement('DELETE FROM snapshots WHERE snapshot_source_id IS NULL');

        DB::statement('ALTER TABLE snapshots ALTER COLUMN snapshot_source_type SET NOT NULL');
        DB::statement('ALTER TABLE snapshots ALTER COLUMN snapshot_source_id SET NOT NULL');
    }
};
