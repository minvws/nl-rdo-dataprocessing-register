<?php

declare(strict_types=1);

namespace App\Models\Contracts;

use App\Models\Organisation;
use App\Models\Snapshot;
use App\Models\States\SnapshotState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

interface SnapshotSource
{
    /**
     * @return MorphMany<Snapshot, Model&$this>
     */
    public function snapshots(): MorphMany;

    public function getId(): string;

    /**
     * @param array<class-string<SnapshotState>> $snapshotStates
     */
    public function getLatestSnapshotWithState(array $snapshotStates): ?Snapshot;

    public function getOrganisation(): Organisation;

    public function getDisplayName(): string;

    /**
     * @return Collection<class-string<SnapshotSource>, Collection<int, SnapshotSource>>
     */
    public function getRelatedSnapshotSources(): Collection;
}
