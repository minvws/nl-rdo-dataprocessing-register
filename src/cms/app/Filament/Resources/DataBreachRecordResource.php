<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\NavigationGroups\NavigationGroup;
use App\Filament\RelationManagers\AvgProcessorProcessingRecordRelationManager;
use App\Filament\RelationManagers\AvgResponsibleProcessingRecordRelationManager;
use App\Filament\RelationManagers\DocumentRelationManager;
use App\Filament\RelationManagers\ResponsibleRelationManager;
use App\Filament\RelationManagers\WpgProcessingRecordRelationManager;
use App\Filament\Resources\DataBreachRecord\DataBreachRecordResourceForm;
use App\Filament\Resources\DataBreachRecord\DataBreachRecordResourceInfolist;
use App\Filament\Resources\DataBreachRecord\DataBreachRecordResourceTable;
use App\Filament\Resources\DataBreachRecord\Pages;
use App\Models\DataBreachRecord;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Tables\Table;

use function __;

class DataBreachRecordResource extends Resource
{
    protected static bool $hasNavigationBadge = true;
    protected static ?string $model = DataBreachRecord::class;
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?int $navigationSort = 5;

    public static function getNavigationGroup(): ?string
    {
        return __(NavigationGroup::REGISTERS->value);
    }

    public static function form(Form $form): Form
    {
        return DataBreachRecordResourceForm::form($form);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return DataBreachRecordResourceInfolist::infolist($infolist);
    }

    public static function table(Table $table): Table
    {
        return DataBreachRecordResourceTable::table($table);
    }

    public static function getRelations(): array
    {
        return [
            ResponsibleRelationManager::make(),
            AvgResponsibleProcessingRecordRelationManager::make(),
            AvgProcessorProcessingRecordRelationManager::make(),
            WpgProcessingRecordRelationManager::make(),
            DocumentRelationManager::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDataBreachRecords::route('/'),
            'create' => Pages\CreateDataBreachRecord::route('/create'),
            'view' => Pages\ViewDataBreachRecord::route('/{record}'),
            'edit' => Pages\EditDataBreachRecord::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('data_breach_record.model_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('data_breach_record.model_plural');
    }
}
