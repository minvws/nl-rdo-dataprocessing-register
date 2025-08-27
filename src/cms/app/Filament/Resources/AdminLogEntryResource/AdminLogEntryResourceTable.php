<?php

declare(strict_types=1);

namespace App\Filament\Resources\AdminLogEntryResource;

use App\Models\AdminLogEntry;
use App\Services\DateFormatService;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Webmozart\Assert\Assert;

use function __;
use function array_map;
use function is_int;
use function is_string;
use function json_encode;

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
                    ->view('filament.tables.columns.json', static function (AdminLogEntry $record): array {
                        return [
                            'context' => self::buildContextViewData($record),
                        ];
                    }),
            ])
            ->emptyStateHeading(__('admin_log_entry.table_empty_heading'))
            ->emptyStateDescription(null)
            ->defaultSort('id', 'desc')
            ->defaultPaginationPageOption(25);
    }

    /**
     * @return array<array-key, string>
     */
    private static function buildContextViewData(AdminLogEntry $record): array
    {
        return array_map(static function (mixed $value): string {
            if (is_int($value) || is_string($value)) {
                return (string) $value;
            }

            $value = json_encode($value);
            Assert::string($value);

            return $value;
        }, $record->context);
    }
}
