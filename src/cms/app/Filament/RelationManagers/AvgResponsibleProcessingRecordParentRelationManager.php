<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use App\Filament\Resources\AvgResponsibleProcessingRecordResource;

class AvgResponsibleProcessingRecordParentRelationManager extends ParentRelationManager
{
    protected static string $resource = AvgResponsibleProcessingRecordResource::class;
}
