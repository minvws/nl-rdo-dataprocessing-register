<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use App\Filament\Resources\AlgorithmRecordResource;

class AlgorithmRecordParentRelationManager extends ParentRelationManager
{
    protected static string $resource = AlgorithmRecordResource::class;
}
