<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\RelationManagers\WpgProcessingRecordRelationManager;
use App\Filament\Resources\WpgProcessingRecordServiceResource\Pages;
use App\Models\Wpg\WpgProcessingRecordService;

use function __;

class WpgProcessingRecordServiceResource extends LookupListResource
{
    protected static ?string $model = WpgProcessingRecordService::class;
    protected static ?int $navigationSort = 4;

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWpgProcessingRecordServices::route('/'),
            'create' => Pages\CreateWpgProcessingRecordService::route('/create'),
            'edit' => Pages\EditWpgProcessingRecordService::route('/{record}/edit'),
            'view' => Pages\ViewWpgProcessingRecordService::route('/{record}'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            WpgProcessingRecordRelationManager::class,
        ];
    }

    public static function getEmptyStateHeading(): string
    {
        return __('wpg_processing_record_service.table_empty_heading');
    }

    public static function getModelLabel(): string
    {
        return __('wpg_processing_record_service.model_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('wpg_processing_record_service.model_plural');
    }
}
