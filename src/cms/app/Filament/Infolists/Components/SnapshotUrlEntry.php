<?php

declare(strict_types=1);

namespace App\Filament\Infolists\Components;

use App\Models\Contracts\Publishable;
use App\Models\Snapshot;
use Filament\Infolists\Components\TextEntry;
use Webmozart\Assert\Assert;

use function __;

class SnapshotUrlEntry extends TextEntry
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'snapshotSource.public_from')
            ->label(__('snapshot.url'))
            ->visible(static function (Snapshot $snapshot): bool {
                $snapshotSource = $snapshot->snapshotSource;
                if ($snapshotSource instanceof Publishable) {
                    return $snapshotSource->isPublished();
                }

                return false;
            })
            ->state(static function (Snapshot $snapshot): ?string {
                return self::getPublishedUrl($snapshot);
            })
            ->url(static function (Snapshot $snapshot): ?string {
                return self::getPublishedUrl($snapshot);
            })
            ->openUrlInNewTab();
    }

    private static function getPublishedUrl(Snapshot $snapshot): ?string
    {
        $snapshotSource = $snapshot->snapshotSource;
        Assert::isInstanceOf($snapshotSource, Publishable::class);

        return $snapshotSource->getLatestStaticWebsiteSnapshotEntry()?->url;
    }
}
