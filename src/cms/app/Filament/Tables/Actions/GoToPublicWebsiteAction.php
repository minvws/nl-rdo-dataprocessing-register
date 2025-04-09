<?php

declare(strict_types=1);

namespace App\Filament\Tables\Actions;

use App\Models\Contracts\Publishable;
use App\Models\PublicWebsiteSnapshotEntry;
use Filament\Tables\Actions\Action;
use Webmozart\Assert\Assert;

class GoToPublicWebsiteAction extends Action
{
    public static function make(?string $name = 'public-website'): static
    {
        return parent::make($name)
            ->icon('heroicon-o-globe-alt')
            ->label('')
            ->url(static function (Publishable $record): string {
                $publicWebsiteCheck = $record->getLatestPublicWebsiteSnapshotEntry();
                Assert::isInstanceOf($publicWebsiteCheck, PublicWebsiteSnapshotEntry::class);

                return $publicWebsiteCheck->url;
            })
            ->openUrlInNewTab()
            ->visible(static function (Publishable $record): bool {
                return $record->isPublished();
            });
    }
}
