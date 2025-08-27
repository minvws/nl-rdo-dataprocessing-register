<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Organisation;
use App\Models\System;
use Illuminate\Database\Console\Migrations\BaseCommand;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface;
use Webmozart\Assert\Assert;

use function array_merge;
use function sprintf;

class SystemUndouble extends BaseCommand
{
    protected $signature = 'app:system-undouble';
    protected $description = 'Undouble system entities in application';

    public function handle(LoggerInterface $logger): int
    {
        $this->info('starting system undouble process');
        $organisations = Organisation::all();

        foreach ($organisations as $organisation) {
            $this->info(sprintf('starting system undouble process for organisation: %s', $organisation->name));

            $systems = System::select('description')
                ->selectRaw('COUNT(description)')
                ->where('organisation_id', $organisation->id)
                ->groupBy('description')
                ->havingRaw('COUNT(description) > 1')
                ->get();

            if ($systems->isEmpty()) {
                $this->info(sprintf('- no double systems found for organisation: %s', $organisation->name));
                continue;
            }

            $systemCollection = System::where('organisation_id', $organisation->id)
                ->where('description', $systems->first()->description)
                ->orderBy('created_at')
                ->get();

            $primarySystem = $systemCollection->shift();
            Assert::isInstanceOf($primarySystem, System::class);

            $this->info('- updating system relatables');
            $systemRelatableIds = $systemCollection->pluck('id')->toArray();
            DB::table('system_relatables')
                ->whereIn('system_id', $systemRelatableIds)
                ->update(['system_id' => $primarySystem->id]);

            $logger->debug('Updated system_relatables for organisation', [
                'organisationId' => $organisation->id->toString(),
                'systemId' => $primarySystem->id,
                'systemRelatableIds' => $systemRelatableIds,
            ]);

            $this->info('- updating related snapshot sources');
            $relatedSnapshotSource = DB::table('related_snapshot_sources')
                ->where('snapshot_source_type', System::class)
                ->whereIn('snapshot_source_id', $systemRelatableIds)
                ->first();

            if ($relatedSnapshotSource !== null) {
                DB::table('related_snapshot_sources')
                    ->insertOrIgnore(array_merge((array) $relatedSnapshotSource, ['snapshot_source_id' => $primarySystem->id]));
            }
            DB::table('related_snapshot_sources')
                ->where('snapshot_source_type', System::class)
                ->whereIn('snapshot_source_id', $systemRelatableIds)
                ->delete();

            $logger->debug('Updated snapshot sources for systems', [
                'systemId' => $primarySystem->id,
                'snapshotSourceIds' => $systemRelatableIds,
            ]);

            $this->info('- delete duplicate systems');
            $systemCollection->each(static function (System $system) use ($logger): void {
                $system->delete();
                $logger->debug('Deleted duplicate system', [
                    'systemId' => $system->id,
                ]);
            });
        }
        $this->info('finished system undouble process');

        $this->output->success('Done!');

        return self::SUCCESS;
    }
}
