<?php

declare(strict_types=1);

namespace App\Models\Contracts;

use App\Collections\StaticWebsiteSnapshotEntryCollection;
use App\Models\Organisation;
use App\Models\Snapshot;
use App\Models\States\SnapshotState;
use App\Models\StaticWebsiteSnapshotEntry;
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
    public function getStaticWebsiteSnapshotEntries(Collection $snapshotIds): StaticWebsiteSnapshotEntryCollection;

    public function getLatestStaticWebsiteSnapshotEntry(): ?StaticWebsiteSnapshotEntry;

    /**
     * @param array<class-string<SnapshotState>> $snapshotStates
     */
    public function getLatestSnapshotWithState(array $snapshotStates): ?Snapshot;

    public function shouldBePublished(): bool;

    public function observeUpdated(): void;

    public function observeDeleted(): void;
}
