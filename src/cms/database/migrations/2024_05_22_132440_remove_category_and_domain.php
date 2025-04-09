<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('category_relatables');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('domains');

        $this->removeSnapshotsByType('App\Models\Category');
        $this->removeSnapshotsByType('App\Models\Domain');
    }

    private function removeSnapshotsByType(string $sourceType): void
    {
        $snapshots = DB::table('snapshots')
            ->where(['snapshot_source_type' => $sourceType])
            ->get();

        foreach ($snapshots as $snapshot) {
            DB::table('snapshot_data')
                ->where(['snapshot_id' => $snapshot->id])
                ->delete();

            DB::table('snapshots')
                ->where(['id' => $snapshot->id])
                ->delete();
        }
    }
};
