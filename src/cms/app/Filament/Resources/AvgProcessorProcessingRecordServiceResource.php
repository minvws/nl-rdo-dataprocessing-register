<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\RelationManagers\AvgProcessorProcessingRecordRelationManager;
use App\Filament\Resources\AvgProcessorProcessingRecordServiceResource\Pages;
use App\Models\Avg\AvgProcessorProcessingRecordService;

use function __;

class AvgProcessorProcessingRecordServiceResource extends LookupListResource
{
    protected static ?string $model = AvgProcessorProcessingRecordService::class;
    protected static ?int $navigationSort = 2;

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAvgProcessorProcessingRecordServices::route('/'),
            'create' => Pages\CreateAvgProcessorProcessingRecordService::route('/create'),
            'edit' => Pages\EditAvgProcessorProcessingRecordService::route('/{record}/edit'),
            'view' => Pages\ViewAvgProcessorProcessingRecordService::route('/{record}'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            AvgProcessorProcessingRecordRelationManager::class,
        ];
    }

    public static function getEmptyStateHeading(): string
    {
        return __('avg_processor_processing_record_service.table_empty_heading');
    }

    public static function getModelLabel(): string
    {
        return __('avg_processor_processing_record_service.model_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('avg_processor_processing_record_service.model_plural');
    }
}
