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
        DB::statement(
            "CREATE UNIQUE INDEX
                snapshots_relatable_state
            ON snapshots (snapshot_relatable_type, snapshot_relatable_id, state)
            WHERE state != 'obsolete';",
        );
    }

    public function down(): void
    {
        Schema::table('snapshots', static function (Blueprint $table): void {
            $table->dropIndex('snapshots_relatable_state');
        });
    }
};
