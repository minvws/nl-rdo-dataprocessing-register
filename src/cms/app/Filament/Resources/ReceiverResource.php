<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\NavigationGroups\NavigationGroup;
use App\Filament\RelationManagers\AvgProcessorProcessingRecordRelationManager;
use App\Filament\RelationManagers\AvgResponsibleProcessingRecordRelationManager;
use App\Filament\RelationManagers\SnapshotsRelationManager;
use App\Filament\Resources\ReceiverResource\Pages;
use App\Filament\Resources\ReceiverResource\ReceiverResourceForm;
use App\Filament\Resources\ReceiverResource\ReceiverResourceInfolist;
use App\Filament\Resources\ReceiverResource\ReceiverResourceTable;
use App\Models\Receiver;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Tables\Table;

use function __;

class ReceiverResource extends Resource
{
    protected static ?string $model = Receiver::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return __(NavigationGroup::MANAGEMENT->value);
    }

    public static function form(Form $form): Form
    {
        return ReceiverResourceForm::form($form);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return ReceiverResourceInfolist::infolist($infolist);
    }

    public static function table(Table $table): Table
    {
        return ReceiverResourceTable::table($table);
    }

    public static function getRelations(): array
    {
        return [
            SnapshotsRelationManager::class,
            AvgResponsibleProcessingRecordRelationManager::class,
            AvgProcessorProcessingRecordRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReceivers::route('/'),
            'create' => Pages\CreateReceiver::route('/create'),
            'view' => Pages\ViewReceiver::route('/{record}'),
            'edit' => Pages\EditReceiver::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('receiver.model_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('receiver.model_plural');
    }
}
