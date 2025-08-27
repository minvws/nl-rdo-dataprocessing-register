<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Collections\PublicWebsiteSnapshotEntryCollection;
use App\Events\PublicWebsite;
use App\Events\StaticWebsite;
use App\Models\PublicWebsiteSnapshotEntry;
use App\Models\States\Snapshot\Established;
use App\Observers\PublishableObserver;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Webmozart\Assert\Assert;

/**
 * @property CarbonImmutable|null $public_from
 */
trait IsPublishable
{
    final public static function bootIsPublishable(): void
    {
        static::observe(PublishableObserver::class);
    }

    final public function getPublicIdentifier(): string
    {
        return $this->getNumber();
    }

    final public function getPublicFrom(): ?CarbonImmutable
    {
        return $this->public_from;
    }

    final public function isPublished(): bool
    {
        $publicWebsiteSnapshotEntry = $this->getLatestPublicWebsiteSnapshotEntry();

        if ($publicWebsiteSnapshotEntry === null) {
            return false;
        }

        return $publicWebsiteSnapshotEntry->end_date === null;
    }

    final public function getPublicWebsiteSnapshotEntries(Collection $snapshotIds): PublicWebsiteSnapshotEntryCollection
    {
        $publicWebsiteSnapshotEntries = PublicWebsiteSnapshotEntry::whereIn('snapshot_id', $snapshotIds)
            ->orderBy('start_date', 'desc')
            ->get();

        Assert::isInstanceOf($publicWebsiteSnapshotEntries, PublicWebsiteSnapshotEntryCollection::class);

        return $publicWebsiteSnapshotEntries;
    }

    final public function getLatestPublicWebsiteSnapshotEntry(): ?PublicWebsiteSnapshotEntry
    {
        $snapshot = $this->getLatestSnapshotWithState([Established::class]);

        if ($snapshot === null) {
            return null;
        }

        return PublicWebsiteSnapshotEntry::where('snapshot_id', $snapshot->id)
            ->orderBy('start_date', 'desc')
            ->first();
    }

    final public function shouldBePublished(): bool
    {
        if ($this->public_from === null) {
            return false;
        }

        return $this->public_from->isPast();
    }

    final public function observeUpdated(): void
    {
        $originalPublicFrom = $this->getOriginal('public_from');
        Assert::nullOrIsInstanceOf($originalPublicFrom, CarbonInterface::class);
        $originalPublicFromIsPast = $originalPublicFrom !== null && $originalPublicFrom->isPast();

        $currentPublicFrom = $this->public_from;
        $currentPublicFromIsPast = $currentPublicFrom !== null && $currentPublicFrom->isPast();

        if ($currentPublicFromIsPast && !$originalPublicFromIsPast) {
            $this->dispatchBuildEvent();
            return;
        }

        if (!$currentPublicFromIsPast && $originalPublicFromIsPast) {
            $this->dispatchBuildEvent();
        }
    }

    final public function observeDeleted(): void
    {
        if ($this->public_from !== null && $this->public_from->isPast()) {
            $this->dispatchBuildEvent();
        }
    }

    private function dispatchBuildEvent(): void
    {
        Log::debug('build event triggered by isPublishable observer');

        PublicWebsite\BuildEvent::dispatch();
        StaticWebsite\BuildEvent::dispatch();
    }
}
