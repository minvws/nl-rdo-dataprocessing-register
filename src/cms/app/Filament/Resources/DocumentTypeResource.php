<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\RelationManagers\DocumentRelationManager;
use App\Filament\Resources\DocumentTypeResource\Pages;
use App\Models\DocumentType;

use function __;

class DocumentTypeResource extends LookupListResource
{
    protected static ?string $model = DocumentType::class;
    protected static ?int $navigationSort = 12;
    protected static ?string $tenantRelationshipName = 'documentTypes';

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocumentTypes::route('/'),
            'create' => Pages\CreateDocumentType::route('/create'),
            'edit' => Pages\EditDocumentType::route('/{record}/edit'),
            'view' => Pages\ViewDocumentType::route('/{record}'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            DocumentRelationManager::class,
        ];
    }

    public static function getEmptyStateHeading(): string
    {
        return __('document_type.table_empty_heading');
    }

    public static function getModelLabel(): string
    {
        return __('document_type.model_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('document_type.model_plural');
    }
}
