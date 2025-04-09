<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use App\Filament\Resources\AvgProcessorProcessingRecordResource;

class AvgProcessorProcessingRecordParentRelationManager extends ParentRelationManager
{
    protected static string $resource = AvgProcessorProcessingRecordResource::class;
}
