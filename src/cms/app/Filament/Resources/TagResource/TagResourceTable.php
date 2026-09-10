<?php

declare(strict_types=1);

namespace App\Filament\Resources\TagResource;

use App\Filament\Tables\Columns\CreatedAtColumn;
use App\Filament\Tables\Columns\UpdatedAtColumn;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use function __;

class TagResourceTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                TextColumn::make('name')
                    ->label(__('general.name'))
                    ->searchable()
                    ->sortable(),
                CreatedAtColumn::make(),
                UpdatedAtColumn::make(),
            ])
            ->defaultSort('tags.updated_at', 'desc')
            ->emptyStateHeading(__('tag.table_empty_heading'))
            ->emptyStateDescription(null)
            ->recordActionsColumnLabel(__('general.edit'))
            ->recordActions([
                EditAction::make()
                    ->hiddenLabel()
                    ->tooltip(static fn (EditAction $action) => $action->getLabel()),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }
}
