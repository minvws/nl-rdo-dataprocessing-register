<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\NavigationGroups\NavigationGroup;
use App\Filament\RelationManagers\AvgProcessorProcessingRecordChildrenRelationManager;
use App\Filament\RelationManagers\AvgProcessorProcessingRecordParentRelationManager;
use App\Filament\RelationManagers\ContactPersonRelationManager;
use App\Filament\RelationManagers\DocumentRelationManager;
use App\Filament\RelationManagers\ProcessorRelationManager;
use App\Filament\RelationManagers\ReceiverRelationManager;
use App\Filament\RelationManagers\ResponsibleRelationManager;
use App\Filament\RelationManagers\SnapshotsRelationManager;
use App\Filament\RelationManagers\SystemRelationManager;
use App\Filament\Resources\AvgProcessorProcessingRecordResource\AvgProcessorProcessingRecordResourceForm;
use App\Filament\Resources\AvgProcessorProcessingRecordResource\AvgProcessorProcessingRecordResourceInfolist;
use App\Filament\Resources\AvgProcessorProcessingRecordResource\AvgProcessorProcessingRecordResourceTable;
use App\Filament\Resources\AvgProcessorProcessingRecordResource\Pages;
use App\Models\Avg\AvgProcessorProcessingRecord;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Tables\Table;

use function __;

class AvgProcessorProcessingRecordResource extends Resource
{
    protected static bool $hasNavigationBadge = true;
    protected static ?string $model = AvgProcessorProcessingRecord::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return __(NavigationGroup::REGISTERS->value);
    }

    public static function form(Form $form): Form
    {
        return AvgProcessorProcessingRecordResourceForm::form($form);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return AvgProcessorProcessingRecordResourceInfolist::infolist($infolist);
    }

    public static function table(Table $table): Table
    {
        return AvgProcessorProcessingRecordResourceTable::table($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAvgProcessorProcessingRecords::route('/'),
            'create' => Pages\CreateAvgProcessorProcessingRecord::route('/create'),
            'view' => Pages\ViewAvgProcessorProcessingRecord::route('/{record}'),
            'edit' => Pages\EditAvgProcessorProcessingRecord::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            SnapshotsRelationManager::class,
            AvgProcessorProcessingRecordParentRelationManager::class,
            AvgProcessorProcessingRecordChildrenRelationManager::class,
            ResponsibleRelationManager::class,
            ProcessorRelationManager::class,
            ReceiverRelationManager::class,
            SystemRelationManager::class,
            ContactPersonRelationManager::class,
            DocumentRelationManager::class,
        ];
    }

    public static function getModelLabel(): string
    {
        return __('avg_processor_processing_record.model_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('avg_processor_processing_record.model_plural');
    }
}
