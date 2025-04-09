<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use App\Filament\Resources\AlgorithmMetaSchemaResource;

class AlgorithmMetaSchemaRelationManager extends RelationManager
{
    protected static string $languageFile = 'algorithm_meta_schema';
    protected static string $relationship = 'algorithmMetaSchema';
    protected static string $resource = AlgorithmMetaSchemaResource::class;
}
