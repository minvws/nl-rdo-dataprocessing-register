<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\RelationManagers\ContactPersonRelationManager;
use App\Filament\Resources\ContactPersonPositionResource\Pages;
use App\Models\ContactPersonPosition;

use function __;

class ContactPersonPositionResource extends LookupListResource
{
    protected static ?string $model = ContactPersonPosition::class;
    protected static ?int $navigationSort = 7;

    public static function getRelations(): array
    {
        return [
            ContactPersonRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactPersonPositions::route('/'),
            'create' => Pages\CreateContactPersonPosition::route('/create'),
            'edit' => Pages\EditContactPersonPosition::route('/{record}/edit'),
            'view' => Pages\ViewContactPersonPosition::route('/{record}'),
        ];
    }

    public static function getEmptyStateHeading(): string
    {
        return __('contact_person_position.table_empty_heading');
    }

    public static function getModelLabel(): string
    {
        return __('contact_person_position.model_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('contact_person_position.model_plural');
    }
}
