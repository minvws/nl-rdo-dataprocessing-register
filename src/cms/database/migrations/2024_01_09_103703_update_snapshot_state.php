<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('snapshots')
            ->where(['state' => 'published'])
            ->update(['state' => 'established']);
        DB::table('snapshot_transitions')
            ->where(['state' => 'published'])
            ->update(['state' => 'established']);
    }

    public function down(): void
    {
        DB::table('snapshots')
            ->where(['state' => 'established'])
            ->update(['state' => 'published']);
        DB::table('snapshot_transitions')
            ->where(['state' => 'established'])
            ->update(['state' => 'published']);
    }
};
