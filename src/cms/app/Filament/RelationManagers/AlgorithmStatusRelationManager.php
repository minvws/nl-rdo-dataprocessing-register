<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use App\Filament\Resources\AlgorithmStatusResource;

class AlgorithmStatusRelationManager extends RelationManager
{
    protected static string $languageFile = 'algorithm_status';
    protected static string $relationship = 'algorithmStatus';
    protected static string $resource = AlgorithmStatusResource::class;
}
