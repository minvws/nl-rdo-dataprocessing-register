<?php

declare(strict_types=1);

namespace App\Filament\Tables\Actions;

use App\Models\Contracts\Publishable;
use App\Models\StaticWebsiteSnapshotEntry;
use Filament\Tables\Actions\Action;
use Webmozart\Assert\Assert;

class GoToStaticWebsiteAction extends Action
{
    public static function make(?string $name = 'static-website'): static
    {
        return parent::make($name)
            ->icon('heroicon-o-globe-alt')
            ->label('')
            ->url(static function (Publishable $record): string {
                $staticWebsiteCheck = $record->getLatestStaticWebsiteSnapshotEntry();
                Assert::isInstanceOf($staticWebsiteCheck, StaticWebsiteSnapshotEntry::class);

                return $staticWebsiteCheck->url;
            })
            ->openUrlInNewTab()
            ->visible(static function (Publishable $record): bool {
                return $record->isPublished();
            });
    }
}
