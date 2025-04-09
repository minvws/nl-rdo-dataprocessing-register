<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\NavigationGroups\NavigationGroup;
use App\Filament\RelationManagers\AvgResponsibleProcessingRecordChildrenRelationManager;
use App\Filament\RelationManagers\AvgResponsibleProcessingRecordParentRelationManager;
use App\Filament\RelationManagers\ContactPersonRelationManager;
use App\Filament\RelationManagers\DocumentRelationManager;
use App\Filament\RelationManagers\ProcessorRelationManager;
use App\Filament\RelationManagers\ReceiverRelationManager;
use App\Filament\RelationManagers\ResponsibleRelationManager;
use App\Filament\RelationManagers\SnapshotsRelationManager;
use App\Filament\RelationManagers\SystemRelationManager;
use App\Filament\RelationManagers\TagRelationManager;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource\AvgResponsibleProcessingRecordResourceForm;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource\AvgResponsibleProcessingRecordResourceInfolist;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource\AvgResponsibleProcessingRecordResourceTable;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource\Pages;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

use function __;

class AvgResponsibleProcessingRecordResource extends Resource
{
    protected static bool $hasNavigationBadge = true;
    protected static ?string $model = AvgResponsibleProcessingRecord::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return __(NavigationGroup::REGISTERS->value);
    }

    public static function form(Form $form): Form
    {
        return AvgResponsibleProcessingRecordResourceForm::form($form);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return AvgResponsibleProcessingRecordResourceInfolist::infolist($infolist);
    }

    public static function table(Table $table): Table
    {
        return AvgResponsibleProcessingRecordResourceTable::table($table);
    }

    /**
     * @return Builder<AvgResponsibleProcessingRecord>
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'stakeholders.stakeholderDataItems',
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SnapshotsRelationManager::class,
            AvgResponsibleProcessingRecordParentRelationManager::class,
            AvgResponsibleProcessingRecordChildrenRelationManager::class,
            TagRelationManager::class,
            ResponsibleRelationManager::class,
            ProcessorRelationManager::class,
            ReceiverRelationManager::class,
            SystemRelationManager::class,
            ContactPersonRelationManager::class,
            DocumentRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAvgResponsibleProcessingRecords::route('/'),
            'create' => Pages\CreateAvgResponsibleProcessingRecord::route('/create'),
            'view' => Pages\ViewAvgResponsibleProcessingRecord::route('/{record}'),
            'edit' => Pages\EditAvgResponsibleProcessingRecord::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('avg_responsible_processing_record.model_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('avg_responsible_processing_record.model_plural');
    }
}
