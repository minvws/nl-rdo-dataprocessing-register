<?php

declare(strict_types=1);

namespace App\Models\Contracts;

use App\Collections\PublicWebsiteSnapshotEntryCollection;
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

    public function isPublished(): bool;

    /**
     * @param Collection<int, string> $snapshotIds
     */
    public function getPublicWebsiteSnapshotEntries(Collection $snapshotIds): PublicWebsiteSnapshotEntryCollection;

    public function getLatestPublicWebsiteSnapshotEntry(): ?PublicWebsiteSnapshotEntry;

    /**
     * @param array<class-string<SnapshotState>> $snapshotStates
     */
    public function getLatestSnapshotWithState(array $snapshotStates): ?Snapshot;

    public function shouldBePublished(): bool;

    public function observeUpdated(): void;

    public function observeDeleted(): void;
}
