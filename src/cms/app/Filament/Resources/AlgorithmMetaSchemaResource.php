<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\RelationManagers\AlgorithmRecordRelationManager;
use App\Filament\Resources\AlgorithmMetaSchemaResource\Pages;
use App\Models\Algorithm\AlgorithmMetaSchema;

use function __;

class AlgorithmMetaSchemaResource extends LookupListResource
{
    protected static ?string $model = AlgorithmMetaSchema::class;
    protected static ?int $navigationSort = 8;
    protected static ?string $tenantRelationshipName = 'algorithmMetaSchemas';

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAlgorithmMetaSchemas::route('/'),
            'create' => Pages\CreateAlgorithmMetaSchema::route('/create'),
            'edit' => Pages\EditAlgorithmMetaSchema::route('/{record}/edit'),
            'view' => Pages\ViewAlgorithmMetaSchema::route('/{record}'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            AlgorithmRecordRelationManager::class,
        ];
    }

    public static function getEmptyStateHeading(): string
    {
        return __('algorithm_meta_schema.table_empty_heading');
    }

    public static function getModelLabel(): string
    {
        return __('algorithm_meta_schema.model_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('algorithm_meta_schema.model_plural');
    }
}
