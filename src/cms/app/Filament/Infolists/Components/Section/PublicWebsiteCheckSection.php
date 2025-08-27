<?php

declare(strict_types=1);

namespace App\Filament\Infolists\Components\Section;

use App\Models\Contracts\Publishable;
use App\Services\DateFormatService;
use Filament\Infolists\Components\Component;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;

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
            ->badge()
            ->color(static function () use ($record): string {
                return $record->isPublished() ? 'success' : 'danger';
            })
            ->formatStateUsing(static function () use ($record): string {
                return $record->isPublished()
                    ? __('public_website.public_from_section.public_state_public')
                    : __('public_website.public_from_section.public_state_not_public');
            });

        /** @var Collection<int, string> $snapshotIds */
        $snapshotIds = $record->snapshots()->pluck('id');

        $publicWebsiteSnapshotEntries = $record->getPublicWebsiteSnapshotEntries($snapshotIds);
        if ($publicWebsiteSnapshotEntries->isNotEmpty()) {
            $schema[] = TextEntry::make('id')
                ->label(__('public_website.public_from_section.history'))
                ->formatStateUsing(static function (): string {
                    return '';
                });

            $publicWebsiteHistoryItems = new Collection();

            foreach ($publicWebsiteSnapshotEntries as $publicWebsiteSnapshotEntry) {
                if ($publicWebsiteSnapshotEntry->end_date === null) {
                    $startDate = DateFormatService::toDateTime($publicWebsiteSnapshotEntry->start_date);
                    Assert::notNull($startDate);

                    $publicWebsiteHistoryItems->push(__('public_website.public_from_section.public_history_since', [
                        'start' => $startDate,
                    ]));

                    continue;
                }

                $startDate = DateFormatService::toSentence($publicWebsiteSnapshotEntry->start_date);
                Assert::notNull($startDate);
                $endDate = DateFormatService::toSentence($publicWebsiteSnapshotEntry->end_date);
                Assert::notNull($endDate);

                $publicWebsiteHistoryItems->push(__('public_website.public_from_section.public_history_from_to', [
                    'start' => $startDate,
                    'end' => $endDate,
                ]));
            }

            $schema[] = TextEntry::make('id')
                ->label(__('public_website.public_from_section.public_history'))
                ->view('filament.forms.components.section.public-website-history', ['items' => $publicWebsiteHistoryItems]);
        }

        return $schema;
    }
}
