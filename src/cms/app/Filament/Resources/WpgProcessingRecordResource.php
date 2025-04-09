<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\NavigationGroups\NavigationGroup;
use App\Filament\RelationManagers\ContactPersonRelationManager;
use App\Filament\RelationManagers\DocumentRelationManager;
use App\Filament\RelationManagers\ProcessorRelationManager;
use App\Filament\RelationManagers\ResponsibleRelationManager;
use App\Filament\RelationManagers\SnapshotsRelationManager;
use App\Filament\RelationManagers\SystemRelationManager;
use App\Filament\RelationManagers\WpgProcessingRecordChildrenRelationManager;
use App\Filament\RelationManagers\WpgProcessingRecordParentRelationManager;
use App\Filament\Resources\WpgProcessingRecordResource\Pages;
use App\Filament\Resources\WpgProcessingRecordResource\WpgProcessingRecordResourceForm;
use App\Filament\Resources\WpgProcessingRecordResource\WpgProcessingRecordResourceInfolist;
use App\Filament\Resources\WpgProcessingRecordResource\WpgProcessingRecordResourceTable;
use App\Models\Wpg\WpgProcessingRecord;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Tables\Table;

use function __;

class WpgProcessingRecordResource extends Resource
{
    protected static bool $hasNavigationBadge = true;
    protected static ?string $model = WpgProcessingRecord::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';
    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return __(NavigationGroup::REGISTERS->value);
    }

    public static function form(Form $form): Form
    {
        return WpgProcessingRecordResourceForm::form($form);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return WpgProcessingRecordResourceInfolist::infolist($infolist);
    }

    public static function table(Table $table): Table
    {
        return WpgProcessingRecordResourceTable::table($table);
    }

    public static function getRelations(): array
    {
        return [
            SnapshotsRelationManager::class,
            WpgProcessingRecordParentRelationManager::class,
            WpgProcessingRecordChildrenRelationManager::class,
            ResponsibleRelationManager::class,
            ProcessorRelationManager::class,
            SystemRelationManager::class,
            ContactPersonRelationManager::class,
            DocumentRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWpgProcessingRecords::route('/'),
            'create' => Pages\CreateWpgProcessingRecord::route('/create'),
            'view' => Pages\ViewWpgProcessingRecord::route('/{record}'),
            'edit' => Pages\EditWpgProcessingRecord::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('wpg_processing_record.model_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('wpg_processing_record.model_plural');
    }
}
