<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\RelationManagers\AlgorithmRecordRelationManager;
use App\Filament\Resources\AlgorithmPublicationCategoryResource\Pages;
use App\Models\Algorithm\AlgorithmPublicationCategory;

use function __;

class AlgorithmPublicationCategoryResource extends LookupListResource
{
    protected static ?string $model = AlgorithmPublicationCategory::class;
    protected static ?int $navigationSort = 9;
    protected static ?string $tenantRelationshipName = 'algorithmPublicationCategories';

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAlgorithmPublicationCategories::route('/'),
            'create' => Pages\CreateAlgorithmPublicationCategory::route('/create'),
            'edit' => Pages\EditAlgorithmPublicationCategory::route('/{record}/edit'),
            'view' => Pages\ViewAlgorithmPublicationCategory::route('/{record}'),
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
        return __('algorithm_publication_category.table_empty_heading');
    }

    public static function getModelLabel(): string
    {
        return __('algorithm_publication_category.model_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('algorithm_publication_category.model_plural');
    }
}
