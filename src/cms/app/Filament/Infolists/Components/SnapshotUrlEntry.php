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
    public static function make(string $name = 'snapshotSource.public_from'): static
    {
        return parent::make($name)
            ->label(__('snapshot.url'))
            ->visible(static function (Snapshot $snapshot): bool {
                $snapshotSource = $snapshot->snapshotSource;
                Assert::isInstanceOf($snapshotSource, Publishable::class);

                return $snapshotSource->isPublished();
            })
            ->view('filament.infolists.components.entries.snapshot-url-entry');
    }
}
