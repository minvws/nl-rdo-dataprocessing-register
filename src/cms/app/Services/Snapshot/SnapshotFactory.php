<?php

declare(strict_types=1);

namespace App\Services\Snapshot;

use App\Models\Contracts\SnapshotSource;
use App\Models\Snapshot;
use App\Models\States\SnapshotState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

readonly class SnapshotFactory
{
    public function __construct(
        private SnapshotStateTransitionService $snapshotStateTransitionService,
        private SnapshotDataFactory $snapshotDataFactory,
    ) {
    }

    /**
     * @param Model&SnapshotSource $snapshotSource
     *
     * @throws Throwable
     */
    public function fromSnapshotSource(
        SnapshotSource $snapshotSource,
        ?int $version = null,
        ?SnapshotState $snapshotState = null,
    ): Snapshot {
        return DB::transaction(function () use ($snapshotSource, $version, $snapshotState): Snapshot {
            /** @var SnapshotState $defaultSnapstState */
            $defaultSnapstState = SnapshotState::make(SnapshotState::DEFAULT_STATE, $snapshotSource);

            if ($version === null) {
                $maxSnapshotVersion = $snapshotSource->snapshots()->max('version');
                $version = $maxSnapshotVersion + 1;
            }

            $this->snapshotStateTransitionService->transitionSnapshotsToObsolete($snapshotSource, $defaultSnapstState);

            /** @var Snapshot $snapshot */
            $snapshot = $snapshotSource->snapshots()->create([
                'name' => Str::take($snapshotSource->getDisplayName(), 255),
                'organisation_id' => $snapshotSource->getOrganisation()->id,
                'version' => $version,
                'state' => $snapshotState ?? $defaultSnapstState,
            ]);

            $this->snapshotDataFactory->createDataForSnapshot($snapshot);
            foreach ($snapshotSource->getRelatedSnapshotSources() as $relatedSnapshotSourceClass => $relatedSnapshotSourceCollection) {
                foreach ($relatedSnapshotSourceCollection as $relatedSnapshotSource) {
                    $snapshot->relatedSnapshotSources()->create([
                        'snapshot_id' => $snapshot->id,
                        'snapshot_source_type' => $relatedSnapshotSourceClass,
                        'snapshot_source_id' => $relatedSnapshotSource->getId(),
                    ]);
                }
            }

            return $snapshot;
        });
    }
}
