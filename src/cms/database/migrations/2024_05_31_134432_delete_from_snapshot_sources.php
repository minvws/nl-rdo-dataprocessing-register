<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->deleteFromSnapshotSources('App\Models\Category');
        $this->deleteFromSnapshotSources('App\Models\Domain');
        $this->deleteFromSnapshotSources('App\Models\ProcessorType');
        $this->deleteFromSnapshotSources('App\Models\ResponsibleType');
        $this->deleteFromSnapshotSources('App\Models\Avg\AvgGoalLegalBase');
    }

    private function deleteFromSnapshotSources(string $sourceType): void
    {
        DB::table('related_snapshot_sources')
            ->where('snapshot_source_type', $sourceType)
            ->delete();

        DB::table('snapshots')
            ->where('snapshot_source_type', $sourceType)
            ->delete();
    }
};
