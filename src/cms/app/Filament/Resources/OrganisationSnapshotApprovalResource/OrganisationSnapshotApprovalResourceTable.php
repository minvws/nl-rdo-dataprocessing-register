<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrganisationSnapshotApprovalResource;

use App\Facades\Snapshot as SnapshotFacade;
use App\Filament\Tables\Columns\CreatedAtColumn;
use App\Filament\Tables\Columns\SnapshotSourceTypeColumn;
use App\Filament\Tables\Columns\SnapshotStateColumn;
use App\Models\Scopes\OrderByCreatedAtAscScope;
use App\Models\Snapshot;
use App\Models\States\Snapshot\Approved;
use App\Models\States\Snapshot\InReview;
use App\Models\States\SnapshotState;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

use function __;
use function class_basename;
use function collect;
use function sprintf;

class OrganisationSnapshotApprovalResourceTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SnapshotSourceTypeColumn::make(),
                TextColumn::make('name')
                    ->label(__('snapshot.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('version')
                    ->label(__('snapshot.version'))
                    ->alignCenter()
                    ->searchable()
                    ->sortable(),
                SnapshotStateColumn::make(),
                TextColumn::make('snapshotApprovals')
                    ->label(__('snapshot_approval.model_plural'))
                    ->alignCenter()
                    ->formatStateUsing(static function (Snapshot $snapshot): string {
                        return sprintf('%s / %s', SnapshotFacade::countApproved($snapshot), SnapshotFacade::countTotal($snapshot));
                    }),
                CreatedAtColumn::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading(__('snapshot.table_empty_heading'))
            ->emptyStateDescription(null)
            ->filters([
                SelectFilter::make('snapshot_source_type')
                    ->label(__('snapshot.snapshot_source_type'))
                    ->multiple()
                    ->options(static function (): array {
                        $models = Snapshot::withoutGlobalScope(OrderByCreatedAtAscScope::class)
                            ->distinct('snapshot_source_type')
                            ->get()
                            ->keyBy('snapshot_source_type')
                            ->map(static function (Snapshot $snapshot): string {
                                return __(sprintf('%s.model_singular', Str::snake(class_basename($snapshot->snapshot_source_type))));
                            });

                        return $models->toArray();
                    }),
                SelectFilter::make('state')
                    ->label(__('snapshot.state'))
                    ->multiple()
                    ->default([
                        Approved::$name,
                        InReview::$name,
                    ])
                    ->options(static function (): array {
                        return collect(SnapshotState::all())
                            ->map(static function ($value, $key): string {
                                return __(sprintf('snapshot_state.label.%s', $key));
                            })
                            ->toArray();
                    }),
            ]);
    }
}
