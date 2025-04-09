<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\NavigationGroups\NavigationGroup;
use App\Filament\RelationManagers\AvgProcessorProcessingRecordRelationManager;
use App\Filament\RelationManagers\AvgResponsibleProcessingRecordRelationManager;
use App\Filament\RelationManagers\SnapshotsRelationManager;
use App\Filament\RelationManagers\WpgProcessingRecordRelationManager;
use App\Filament\Resources\SystemResource\Pages;
use App\Filament\Resources\SystemResource\SystemResourceForm;
use App\Filament\Resources\SystemResource\SystemResourceInfolist;
use App\Filament\Resources\SystemResource\SystemResourceTable;
use App\Models\System;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Tables\Table;

use function __;

class SystemResource extends Resource
{
    protected static ?string $model = System::class;
    protected static ?string $navigationIcon = 'heroicon-o-command-line';
    protected static ?int $navigationSort = 8;

    public static function getNavigationGroup(): ?string
    {
        return __(NavigationGroup::MANAGEMENT->value);
    }

    public static function form(Form $form): Form
    {
        return SystemResourceForm::form($form);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return SystemResourceInfolist::infolist($infolist);
    }

    public static function table(Table $table): Table
    {
        return SystemResourceTable::table($table);
    }

    public static function getRelations(): array
    {
        return [
            SnapshotsRelationManager::class,
            AvgResponsibleProcessingRecordRelationManager::class,
            AvgProcessorProcessingRecordRelationManager::class,
            WpgProcessingRecordRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSystems::route('/'),
            'create' => Pages\CreateSystem::route('/create'),
            'view' => Pages\ViewSystem::route('/{record}'),
            'edit' => Pages\EditSystem::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('system.model_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('system.model_plural');
    }
}
