<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use App\Filament\Resources\DocumentResource;

class DocumentRelationManager extends RelationManager
{
    protected static string $languageFile = 'document';
    protected static string $relationship = 'documents';
    protected static string $resource = DocumentResource::class;
}
