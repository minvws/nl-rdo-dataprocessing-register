<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\RegisterLayout;
use App\Facades\Authentication;
use App\Filament\NavigationGroups\NavigationGroup;
use App\Filament\RelationManagers\AvgProcessorProcessingRecordRelationManager;
use App\Filament\RelationManagers\AvgResponsibleProcessingRecordRelationManager;
use App\Filament\RelationManagers\DocumentRelationManager;
use App\Filament\RelationManagers\ResponsibleRelationManager;
use App\Filament\RelationManagers\WpgProcessingRecordRelationManager;
use App\Filament\Resources\DataBreachRecord\DataBreachRecordResourceForm;
use App\Filament\Resources\DataBreachRecord\DataBreachRecordResourceInfolist;
use App\Filament\Resources\DataBreachRecord\DataBreachRecordResourceTable;
use App\Filament\Resources\DataBreachRecord\Pages\CreateDataBreachRecord;
use App\Filament\Resources\DataBreachRecord\Pages\EditDataBreachRecord;
use App\Filament\Resources\DataBreachRecord\Pages\ListDataBreachRecords;
use App\Filament\Resources\DataBreachRecord\Pages\ViewDataBreachRecord;
use App\Models\DataBreachRecord;
use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

use function __;

class DataBreachRecordResource extends Resource
{
    protected static bool $hasNavigationBadge = true;
    protected static ?string $model = DataBreachRecord::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';
    protected static ?int $navigationSort = 5;

    public static function getNavigationGroup(): ?string
    {
        return __(NavigationGroup::REGISTERS->value);
    }

    public static function form(Schema $schema): Schema
    {
        return match (Authentication::user()->register_layout) {
            RegisterLayout::STEPS => DataBreachRecordResourceForm::stepsForm($schema),
            RegisterLayout::ONE_PAGE => DataBreachRecordResourceForm::onePageForm($schema),
        };
    }

    public static function infolist(Schema $schema): Schema
    {
        return match (Authentication::user()->register_layout) {
            RegisterLayout::STEPS => DataBreachRecordResourceInfolist::stepsInfolist($schema),
            RegisterLayout::ONE_PAGE => DataBreachRecordResourceInfolist::onePageInfolist($schema),
        };
    }

    public static function table(Table $table): Table
    {
        return DataBreachRecordResourceTable::table($table);
    }

    public static function getRelations(): array
    {
        return [
            DocumentRelationManager::make(),
            ResponsibleRelationManager::make(),
            AvgResponsibleProcessingRecordRelationManager::make(),
            AvgProcessorProcessingRecordRelationManager::make(),
            WpgProcessingRecordRelationManager::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDataBreachRecords::route('/'),
            'create' => CreateDataBreachRecord::route('/create'),
            'view' => ViewDataBreachRecord::route('/{record}'),
            'edit' => EditDataBreachRecord::route('/{record}/edit'),
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
