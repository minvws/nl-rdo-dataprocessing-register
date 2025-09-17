<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Collections\StaticWebsiteSnapshotEntryCollection;
use App\Events\StaticWebsite\BuildEvent;
use App\Models\States\Snapshot\Established;
use App\Models\StaticWebsiteSnapshotEntry;
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
        $staticWebsiteSnapshotEntry = $this->getLatestStaticWebsiteSnapshotEntry();

        if ($staticWebsiteSnapshotEntry === null) {
            return false;
        }

        return $staticWebsiteSnapshotEntry->end_date === null;
    }

    final public function getStaticWebsiteSnapshotEntries(Collection $snapshotIds): StaticWebsiteSnapshotEntryCollection
    {
        $staticWebsiteSnapshotEntries = StaticWebsiteSnapshotEntry::whereIn('snapshot_id', $snapshotIds)
            ->orderBy('start_date', 'desc')
            ->get();

        Assert::isInstanceOf($staticWebsiteSnapshotEntries, StaticWebsiteSnapshotEntryCollection::class);

        return $staticWebsiteSnapshotEntries;
    }

    final public function getLatestStaticWebsiteSnapshotEntry(): ?StaticWebsiteSnapshotEntry
    {
        $snapshot = $this->getLatestSnapshotWithState([Established::class]);

        if ($snapshot === null) {
            return null;
        }

        return StaticWebsiteSnapshotEntry::where('snapshot_id', $snapshot->id)
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

        BuildEvent::dispatch();
    }
}
