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
        Schema::table('snapshots', static function (Blueprint $table): void {
            $table->dropIndex('snapshots_snaps_relat_type_snaps_relat_id_index');
            $table->dropIndex('snapshots_relatable_state');
            $table->dropColumn('snapshot_relatable_id');
            $table->dropColumn('snapshot_relatable_type');

            $snapshotSourceIndex = IndexNameTruncater::index(
                $table->getTable(),
                'snapshot_source_type',
                'snapshot_source_id',
            );
            $table->nullableUuidMorphs('snapshot_source', $snapshotSourceIndex);
        });

        DB::statement("CREATE UNIQUE INDEX
            snapshots_source_state
            ON snapshots (snapshot_source_type, snapshot_source_id, state)
            WHERE state != 'obsolete';
        ");
    }

    public function down(): void
    {
        Schema::table('snapshots', static function (Blueprint $table): void {
            $table->dropIndex('snapshots_snaps_sourc_type_snaps_sourc_id_index');
            $table->dropIndex('snapshots_source_state');
            $table->dropColumn('snapshot_source_id');
            $table->dropColumn('snapshot_source_type');

            $snapshotRelatableIx = IndexNameTruncater::index(
                $table->getTable(),
                'snapshot_relatable_type',
                'snapshot_relatable_id',
            );
            $table->nullableUuidMorphs('snapshot_relatable', $snapshotRelatableIx);
        });

        DB::statement("CREATE UNIQUE INDEX
            snapshots_relatable_state
            ON snapshots (snapshot_relatable_type, snapshot_relatable_id, state)
            WHERE state != 'obsolete';
        ");
    }
};
