<?php

declare(strict_types=1);

use App\Services\SqlExport\IndexNameTruncater;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('snapshots', static function (Blueprint $table): void {
            $table->dropIndex(['snapshotable_type', 'snapshotable_id']);

            $table->dropColumn('snapshotable_type');
            $table->dropColumn('snapshotable_id');

            $snapshotRelatableIx = IndexNameTruncater::index(
                $table->getTable(),
                'snapshot_relatable_type',
                'snapshot_relatable_id',
            );
            $table->nullableUuidMorphs('snapshot_relatable', $snapshotRelatableIx);
        });
    }

    public function down(): void
    {
        Schema::table('snapshots', static function (Blueprint $table): void {
            $snapshotRelatableIx = IndexNameTruncater::index(
                $table->getTable(),
                'snapshot_relatable_type',
                'snapshot_relatable_id',
            );
            $table->dropIndex($snapshotRelatableIx);

            $table->dropColumn('snapshot_relatable_type');
            $table->dropColumn('snapshot_relatable_id');

            $table->nullableUuidMorphs('snapshotable');
        });
    }
};
