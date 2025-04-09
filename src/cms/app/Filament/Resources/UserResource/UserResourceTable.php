<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource;

use App\Filament\Tables\Columns\CreatedAtColumn;
use App\Filament\Tables\Columns\UpdatedAtColumn;
use Filament\Tables\Actions\DetachBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

use function __;

class UserResourceTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('general.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('user.email'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('organisations.name')
                    ->separator(','),
                CreatedAtColumn::make(),
                UpdatedAtColumn::make(),
            ])
            ->defaultSort('users.name')
            ->emptyStateHeading(__('user.table_empty_heading'))
            ->emptyStateDescription(null)
            ->actions([
                EditAction::make()
                    ->label(''),
            ])
            ->bulkActions([
                DetachBulkAction::make(),
            ])
            ->filters([
                SelectFilter::make('organisation')
                    ->label(__('organisation.model_plural'))
                    ->relationship('organisations', 'name'),
            ]);
    }
}
