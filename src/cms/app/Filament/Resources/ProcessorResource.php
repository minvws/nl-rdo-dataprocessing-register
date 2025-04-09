<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\NavigationGroups\NavigationGroup;
use App\Filament\RelationManagers\AvgProcessorProcessingRecordRelationManager;
use App\Filament\RelationManagers\AvgResponsibleProcessingRecordRelationManager;
use App\Filament\RelationManagers\SnapshotsRelationManager;
use App\Filament\RelationManagers\WpgProcessingRecordRelationManager;
use App\Filament\Resources\ProcessorResource\Pages;
use App\Filament\Resources\ProcessorResource\ProcessorResourceForm;
use App\Filament\Resources\ProcessorResource\ProcessorResourceInfolist;
use App\Filament\Resources\ProcessorResource\ProcessorResourceTable;
use App\Models\Processor;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Tables\Table;

use function __;

class ProcessorResource extends Resource
{
    protected static ?string $model = Processor::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return __(NavigationGroup::MANAGEMENT->value);
    }

    public static function form(Form $form): Form
    {
        return ProcessorResourceForm::form($form);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return ProcessorResourceInfolist::infolist($infolist);
    }

    public static function table(Table $table): Table
    {
        return ProcessorResourceTable::table($table);
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
            'index' => Pages\ListProcessors::route('/'),
            'create' => Pages\CreateProcessor::route('/create'),
            'view' => Pages\ViewProcessor::route('/{record}'),
            'edit' => Pages\EditProcessor::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('processor.model_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('processor.model_plural');
    }
}
