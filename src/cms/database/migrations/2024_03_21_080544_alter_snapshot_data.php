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
        DB::table('snapshot_approvals')->truncate();
        DB::table('snapshot_approval_logs')->truncate();
        DB::table('snapshot_data')->truncate();
        DB::table('snapshot_transitions')->truncate();
        DB::table('snapshots')->truncate();

        Schema::table('snapshot_data', static function (Blueprint $table): void {
            $table->dropColumn('private_frontmatter');
            $table->dropColumn('private_markdown');
            $table->dropColumn('public_frontmatter');
        });

        Schema::table('snapshot_data', static function (Blueprint $table): void {
            $table->text('private_markdown')->nullable();
            $table->text('public_frontmatter');
        });
    }

    public function down(): void
    {
        Schema::table('snapshot_data', static function (Blueprint $table): void {
            $table->text('private_frontmatter');
        });
    }
};
