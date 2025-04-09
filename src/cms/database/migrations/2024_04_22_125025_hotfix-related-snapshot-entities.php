<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('related_snapshot_sources')
            ->whereIn('snapshot_source_type', [
                'App\Models\Avg\AvgGoal',
                'App\Models\Stakeholder',
                'App\Models\StakeholderDataItem',
                'App\Models\Avg\WpgGoal',
            ])
            ->delete();

        DB::statement('ALTER TABLE snapshot_data DROP constraint snapshot_data_snapshot_id_foreign');
        DB::statement(
            'ALTER TABLE snapshot_data ADD constraint snapshot_data_snapshot_id_foreign foreign key (snapshot_id) references snapshots(id) on delete cascade',
        );

        DB::statement('ALTER TABLE snapshot_transitions DROP constraint snapshot_transitions_snapshot_id_foreign');
        DB::statement(
            'ALTER TABLE snapshot_transitions ADD constraint snapshot_transitions_snapshot_id_foreign foreign key (snapshot_id) references snapshots(id) on delete cascade',
        );

        DB::table('snapshots')
            ->whereIn('snapshot_source_type', [
                'App\Models\Avg\AvgGoal',
                'App\Models\Stakeholder',
                'App\Models\StakeholderDataItem',
                'App\Models\Avg\WpgGoal',
            ])
            ->delete();
    }
};
