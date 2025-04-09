<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use App\Filament\Resources\AlgorithmPublicationCategoryResource;

class AlgorithmPublicationCategoryRelationManager extends RelationManager
{
    protected static string $languageFile = 'algorithm_publication_category';
    protected static string $relationship = 'algorithmPublicationCategory';
    protected static string $resource = AlgorithmPublicationCategoryResource::class;
}
