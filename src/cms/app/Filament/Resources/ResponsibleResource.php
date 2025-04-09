<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\NavigationGroups\NavigationGroup;
use App\Filament\RelationManagers\AvgProcessorProcessingRecordRelationManager;
use App\Filament\RelationManagers\AvgResponsibleProcessingRecordRelationManager;
use App\Filament\RelationManagers\DataBreachRecordRelationManager;
use App\Filament\RelationManagers\SnapshotsRelationManager;
use App\Filament\RelationManagers\WpgProcessingRecordRelationManager;
use App\Filament\Resources\ResponsibleResource\Pages;
use App\Filament\Resources\ResponsibleResource\ResponsibleResourceForm;
use App\Filament\Resources\ResponsibleResource\ResponsibleResourceInfolist;
use App\Filament\Resources\ResponsibleResource\ResponsibleResourceTable;
use App\Models\Responsible;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Tables\Table;

use function __;

class ResponsibleResource extends Resource
{
    protected static ?string $model = Responsible::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return __(NavigationGroup::MANAGEMENT->value);
    }

    public static function form(Form $form): Form
    {
        return ResponsibleResourceForm::form($form);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return ResponsibleResourceInfolist::infolist($infolist);
    }

    public static function table(Table $table): Table
    {
        return ResponsibleResourceTable::table($table);
    }

    public static function getRelations(): array
    {
        return [
            SnapshotsRelationManager::class,
            AvgResponsibleProcessingRecordRelationManager::class,
            AvgProcessorProcessingRecordRelationManager::class,
            WpgProcessingRecordRelationManager::class,
            DataBreachRecordRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResponsibles::route('/'),
            'create' => Pages\CreateResponsible::route('/create'),
            'view' => Pages\ViewResponsible::route('/{record}'),
            'edit' => Pages\EditResponsible::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('responsible.model_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('responsible.model_plural');
    }
}
