<?php

declare(strict_types=1);

namespace App\Filament\Resources\RelatedSnapshotSourceResource;

use App\Filament\Resources\Resource;
use App\Filament\Tables\Columns\CreatedAtColumn;
use App\Models\Contracts\SnapshotSource;
use App\Models\RelatedSnapshotSource;
use App\Models\Snapshot;
use App\Models\States\Snapshot\Approved;
use App\Models\States\Snapshot\Established;
use App\Models\States\Snapshot\InReview;
use App\Models\States\Snapshot\Obsolete;
use App\Services\DateFormatService;
use Carbon\CarbonInterface;
use Closure;
use Filament\Facades\Filament;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Webmozart\Assert\Assert;

use function __;
use function class_basename;
use function sprintf;

class RelatedSnapshotSourceResourceTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(static function (RelatedSnapshotSource $relatedSnapshotSource): ?string {
                Assert::methodExists($relatedSnapshotSource->snapshotSource, 'trashed');
                if ($relatedSnapshotSource->snapshotSource->trashed()) {
                    return null;
                }

                return self::getRelatedSnapshotSourceUrl($relatedSnapshotSource);
            })
            ->columns([
                TextColumn::make('snapshotSource')
                    ->label(__('snapshot.state'))
                    ->badge()
                    ->color(self::getSnapshotSourceColumnColor())
                    ->formatStateUsing(self::getSnapshotSourceColumnState()),
                TextColumn::make('snapshot_source_id')
                    ->label(__('snapshot.snapshot_source_display_name'))
                    ->limit(25)
                    ->formatStateUsing(static function (RelatedSnapshotSource $relatedSnapshotSource): string {
                        return $relatedSnapshotSource->snapshotSource->getDisplayName();
                    }),
                TextColumn::make('snapshot_latest_established')
                    ->label(__('snapshot.latest_established'))
                    ->dateTime(DateFormatService::FORMAT_DATE_TIME, DateFormatService::getDisplayTimezone())
                    ->default(static function (RelatedSnapshotSource $relatedSnapshotSource): ?CarbonInterface {
                        $snapshot = $relatedSnapshotSource->snapshotSource->getLatestSnapshotWithState([Established::class]);

                        return $snapshot?->created_at;
                    }),
                CreatedAtColumn::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading(__('snapshot.table_empty_heading'))
            ->emptyStateDescription(null)
            ->actions([
                ViewAction::make()
                    ->visible(static function (RelatedSnapshotSource $relatedSnapshotSource): bool {
                        Assert::methodExists($relatedSnapshotSource->snapshotSource, 'trashed');
                        return !$relatedSnapshotSource->snapshotSource->trashed();
                    })
                    ->url(static function (RelatedSnapshotSource $relatedSnapshotSource): string {
                        return self::getRelatedSnapshotSourceUrl($relatedSnapshotSource);
                    }),
            ])
            ->defaultGroup('snapshot_source_type')
            ->groups([
                Group::make('snapshot_source_type')
                    ->getTitleFromRecordUsing(static function (RelatedSnapshotSource $relatedSnapshotSource): string {
                        return __(sprintf('%s.model_singular', Str::snake(class_basename($relatedSnapshotSource->snapshotSource))));
                    })
                    ->titlePrefixedWithLabel(false),
            ])
            ->groupingSettingsHidden()
            ->paginated(false);
    }

    private static function getRelatedSnapshotSourceUrl(RelatedSnapshotSource $relatedSnapshotSource): string
    {
        /** @var class-string<Resource> $resource */
        $resource = Filament::getModelResource($relatedSnapshotSource->snapshot_source_type);

        $url = $resource::getGlobalSearchResultUrl($relatedSnapshotSource->snapshotSource);
        Assert::string($url);

        return $url;
    }

    private static function getSnapshotSourceColumnColor(): Closure
    {
        return static function (SnapshotSource $state): string {
            Assert::methodExists($state, 'trashed');
            if ($state->trashed() === true) {
                return 'danger';
            }

            $snapshotStates = $state->snapshots()
                ->get(['state'])
                ->map(static function (Snapshot $model): string {
                    return $model->state->getValue();
                })
                ->unique();

            if ($snapshotStates->contains(Established::$name)) {
                return Established::$color;
            }

            if ($snapshotStates->contains(Approved::$name)) {
                return Approved::$color;
            }

            if ($snapshotStates->contains(InReview::$name)) {
                return InReview::$color;
            }

            if ($snapshotStates->contains(Obsolete::$name)) {
                return Obsolete::$color;
            }

            return 'gray';
        };
    }

    private static function getSnapshotSourceColumnState(): Closure
    {
        return static function (SnapshotSource $state): string {
            Assert::methodExists($state, 'trashed');
            if ($state->trashed() === true) {
                return __('general.deleted');
            }

            $states = $state->snapshots()
                ->get(['state'])
                ->map(static function (Snapshot $model): string {
                    return $model->state->getValue();
                })
                ->unique();

            if ($states->contains(Established::$name)) {
                return __('snapshot_state.label.established');
            }

            if ($states->contains(Approved::$name)) {
                return __('snapshot_state.label.approved');
            }

            if ($states->contains(InReview::$name)) {
                return __('snapshot_state.label.in_review');
            }

            if ($states->contains(Obsolete::$name)) {
                return __('snapshot_state.label.obsolete');
            }

            return '-';
        };
    }
}
