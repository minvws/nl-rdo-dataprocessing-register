<?php

declare(strict_types=1);

namespace App\Filament\Forms\Components\Section;

use App\Models\Contracts\Publishable;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Illuminate\Support\Collection;

use function __;

class PublicWebsiteCheckSection extends Section
{
    public static function makeTable(): static
    {
        return parent::make(__('public_website.public_from_section.title'))
            ->description(__('public_website.public_from_section.subtitle'))
            ->schema(static function (?Publishable $record): array {
                if ($record === null) {
                    return [];
                }

                return self::getSchema($record);
            });
    }

    /**
     * @return array<Component>
     */
    private static function getSchema(Publishable $record): array
    {
        $schema = [];

        $publicStateContent = $record->isPublished()
            ? __('public_website.public_from_section.public_state_public')
            : __('public_website.public_from_section.public_state_not_public');

        $schema[] = Placeholder::make('public_website_public_state')
            ->label(__('public_website.public_from_section.public_state'))
            ->content($publicStateContent);

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

            $schema[] = Placeholder::make('public_website_snapshot_entry')
                ->label(__('public_website.public_from_section.public_history'))
                ->view('filament.forms.components.section.public-website-history', ['items' => $publicWebsiteHistoryItems]);
        }

        return $schema;
    }
}
