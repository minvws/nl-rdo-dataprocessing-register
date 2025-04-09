<?php

declare(strict_types=1);

namespace App\Filament\Infolists\Components\Section;

use App\Models\Contracts\Publishable;
use Filament\Infolists\Components\Component;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\Collection;

use function __;

class PublicWebsiteCheckSection extends Section
{
    public static function makeTable(): static
    {
        return parent::make(__('public_website.public_from_section.title'))
            ->description(__('public_website.public_from_section.subtitle'))
            ->schema(static function (Publishable $record): array {
                return self::getSchema($record);
            });
    }

    /**
     * @return array<Component>
     */
    private static function getSchema(Publishable $record): array
    {
        $schema = [];

        $schema[] = TextEntry::make('id')
            ->label(__('public_website.public_from_section.public_state'))
            ->formatStateUsing(static function () use ($record): string {
                return $record->isPublished()
                    ? __('public_website.public_from_section.public_state_public')
                    : __('public_website.public_from_section.public_state_not_public');
            });

        /** @var Collection<int, string> $snapshotIds */
        $snapshotIds = $record->snapshots()->pluck('id');

        $publicWebsiteSnapshotEntries = $record->getPublicWebsiteSnapshotEntries($snapshotIds);
        if ($publicWebsiteSnapshotEntries->isNotEmpty()) {
            $publicWebsiteHistoryItems = new Collection();

            foreach ($publicWebsiteSnapshotEntries as $publicWebsiteSnapshotEntry) {
                $format = 'd-m-Y H:i';

                if ($publicWebsiteSnapshotEntry->end_date === null) {
                    $publicWebsiteHistoryItems->push(__('public_website.public_from_section.public_history_since', [
                        'start' => $publicWebsiteSnapshotEntry->start_date->format($format),
                    ]));
                    continue;
                }

                $publicWebsiteHistoryItems->push(__('public_website.public_from_section.public_history_from_to', [
                    'start' => $publicWebsiteSnapshotEntry->start_date->format($format),
                    'end' => $publicWebsiteSnapshotEntry->end_date->format($format),
                ]));
            }

            $schema[] = TextEntry::make('id')
                ->label(__('public_website.public_from_section.public_history'))
                ->view('filament.forms.components.section.public-website-history', ['items' => $publicWebsiteHistoryItems]);
        }

        return $schema;
    }
}
