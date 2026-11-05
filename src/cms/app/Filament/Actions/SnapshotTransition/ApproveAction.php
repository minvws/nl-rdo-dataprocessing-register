<?php

declare(strict_types=1);

namespace App\Filament\Actions\SnapshotTransition;

use App\Filament\Forms\Components\NotEstablishedRelatedSnapshotSourcesCallout;
use App\Models\Contracts\SnapshotSource;
use App\Models\RelatedSnapshotSource;
use App\Models\Snapshot;
use App\Models\States\SnapshotState;
use Illuminate\Support\Collection;

class ApproveAction extends SnapshotTransitionAction
{
    public static function makeForSnapshotState(Snapshot $snapshot, SnapshotState $snapshotState): static
    {
        return parent::makeForSnapshotState($snapshot, $snapshotState)
            ->schema([
                NotEstablishedRelatedSnapshotSourcesCallout::makeForSnapshotSources(static function () use ($snapshot): Collection {
                    return $snapshot->relatedSnapshotSourcesWithoutEstablishedSnapshot()
                        ->get()
                        ->map(static function (RelatedSnapshotSource $relatedSnapshotSource): SnapshotSource {
                            return $relatedSnapshotSource->snapshotSource;
                        });
                }),
            ]);
    }
}
