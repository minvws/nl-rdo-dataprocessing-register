<?php

declare(strict_types=1);

namespace App\Filament\Resources\AdminLogEntryResource;

use App\Services\DateFormatService;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;

use function __;

class AdminLogEntryResourceTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('general.id'))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('general.created_at'))
                    ->dateTime(DateFormatService::FORMAT_DATE_TIME, DateFormatService::getDisplayTimezone())
                    ->sortable(),
                TextColumn::make('message')
                    ->label(__('admin_log_entry.message')),
                ViewColumn::make('context')
                    ->label(__('admin_log_entry.context'))
                    ->view('filament.tables.columns.json'),
            ])
            ->emptyStateHeading(__('admin_log_entry.table_empty_heading'))
            ->emptyStateDescription(null)
            ->defaultSort('id', 'desc')
            ->defaultPaginationPageOption(25);
    }
}
