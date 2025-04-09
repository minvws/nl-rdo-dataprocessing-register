<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\EntityNumber;
use App\Models\PublicWebsiteSnapshotEntry;
use App\Models\States\Snapshot\Established;
use App\Observers\PublishableObserver;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;

trait IsPublishable
{
    public static function bootIsPublishable(): void
    {
        static::observe(PublishableObserver::class);
    }

    public function getPublicIdentifier(): string
    {
        $entityNumber = $this->entityNumber;
        Assert::isInstanceOf($entityNumber, EntityNumber::class);

        return $entityNumber->number;
    }

    public function getPublicFrom(): ?CarbonImmutable
    {
        return $this->public_from;
    }

    public function isDeleted(): bool
    {
        return $this->trashed();
    }

    public function isPublished(): bool
    {
        $publicWebsiteSnapshotEntry = $this->getLatestPublicWebsiteSnapshotEntry();

        if ($publicWebsiteSnapshotEntry === null) {
            return false;
        }

        return $publicWebsiteSnapshotEntry->end_date === null;
    }

    public function getPublicWebsiteSnapshotEntries(Collection $snapshotIds): Collection
    {
        return PublicWebsiteSnapshotEntry::whereIn('snapshot_id', $snapshotIds)
            ->orderBy('start_date', 'desc')
            ->get();
    }

    public function getLatestPublicWebsiteSnapshotEntry(): ?PublicWebsiteSnapshotEntry
    {
        $snapshot = $this->getLatestSnapshotWithState([Established::class]);

        if ($snapshot === null) {
            return null;
        }

        return PublicWebsiteSnapshotEntry::where('snapshot_id', $snapshot->id)
            ->orderBy('start_date', 'desc')
            ->first();
    }

    public function canBePublished(): bool
    {
        if ($this->public_from === null) {
            return false;
        }

        return $this->public_from->isPast();
    }
}
