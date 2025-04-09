<?php

declare(strict_types=1);

namespace App\Models\Contracts;

use App\Models\Organisation;
use App\Models\PublicWebsiteSnapshotEntry;
use App\Models\Snapshot;
use App\Models\States\SnapshotState;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

interface Publishable extends SnapshotSource
{
    public function getOrganisation(): Organisation;

    public function getPublicIdentifier(): string;

    public function getPublicFrom(): ?CarbonImmutable;

    public function isDeleted(): bool;

    public function isPublished(): bool;

    /**
     * @param Collection<int, string> $snapshotIds
     *
     * @return Collection<int, PublicWebsiteSnapshotEntry>
     */
    public function getPublicWebsiteSnapshotEntries(Collection $snapshotIds): Collection;

    public function getLatestPublicWebsiteSnapshotEntry(): ?PublicWebsiteSnapshotEntry;

    public function canBePublished(): bool;

    /**
     * @param array<class-string<SnapshotState>> $snapshotStates
     */
    public function getLatestSnapshotWithState(array $snapshotStates): ?Snapshot;
}
