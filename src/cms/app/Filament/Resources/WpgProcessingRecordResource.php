<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\RegisterLayout;
use App\Facades\Authentication;
use App\Filament\NavigationGroups\NavigationGroup;
use App\Filament\RelationManagers\DocumentRelationManager;
use App\Filament\RelationManagers\ProcessingRecordContactPersonRelationManager;
use App\Filament\RelationManagers\ProcessingRecordUsersRelationManager;
use App\Filament\RelationManagers\ProcessorRelationManager;
use App\Filament\RelationManagers\ResponsibleRelationManager;
use App\Filament\RelationManagers\SnapshotsRelationManager;
use App\Filament\RelationManagers\SystemRelationManager;
use App\Filament\RelationManagers\TagRelationManager;
use App\Filament\RelationManagers\WpgProcessingRecordChildrenRelationManager;
use App\Filament\RelationManagers\WpgProcessingRecordParentRelationManager;
use App\Filament\Resources\WpgProcessingRecordResource\Pages\CreateWpgProcessingRecord;
use App\Filament\Resources\WpgProcessingRecordResource\Pages\EditWpgProcessingRecord;
use App\Filament\Resources\WpgProcessingRecordResource\Pages\ListWpgProcessingRecords;
use App\Filament\Resources\WpgProcessingRecordResource\Pages\ViewWpgProcessingRecord;
use App\Filament\Resources\WpgProcessingRecordResource\WpgProcessingRecordResourceForm;
use App\Filament\Resources\WpgProcessingRecordResource\WpgProcessingRecordResourceInfolist;
use App\Filament\Resources\WpgProcessingRecordResource\WpgProcessingRecordResourceTable;
use App\Models\Wpg\WpgProcessingRecord;
use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

use function __;

class WpgProcessingRecordResource extends Resource
{
    protected static bool $hasNavigationBadge = true;
    protected static ?string $model = WpgProcessingRecord::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-magnifying-glass';
    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return __(NavigationGroup::REGISTERS->value);
    }

    public static function form(Schema $schema): Schema
    {
        return match (Authentication::user()->register_layout) {
            RegisterLayout::STEPS => WpgProcessingRecordResourceForm::stepsForm($schema),
            RegisterLayout::ONE_PAGE => WpgProcessingRecordResourceForm::onePageForm($schema),
        };
    }

    public static function infolist(Schema $schema): Schema
    {
        return match (Authentication::user()->register_layout) {
            RegisterLayout::STEPS => WpgProcessingRecordResourceInfolist::stepsInfolist($schema),
            RegisterLayout::ONE_PAGE => WpgProcessingRecordResourceInfolist::onePageInfolist($schema),
        };
    }

    public static function table(Table $table): Table
    {
        return WpgProcessingRecordResourceTable::table($table);
    }

    public static function getRelations(): array
    {
        return [
            SnapshotsRelationManager::class,
            DocumentRelationManager::class,
            WpgProcessingRecordParentRelationManager::class,
            WpgProcessingRecordChildrenRelationManager::class,
            ResponsibleRelationManager::class,
            ProcessorRelationManager::class,
            SystemRelationManager::class,
            ProcessingRecordUsersRelationManager::class,
            ProcessingRecordContactPersonRelationManager::class,
            TagRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWpgProcessingRecords::route('/'),
            'create' => CreateWpgProcessingRecord::route('/create'),
            'view' => ViewWpgProcessingRecord::route('/{record}'),
            'edit' => EditWpgProcessingRecord::route('/{record}/edit'),
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
