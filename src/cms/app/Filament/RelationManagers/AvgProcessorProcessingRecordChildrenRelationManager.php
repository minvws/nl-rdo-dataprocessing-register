<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use App\Filament\Resources\AvgProcessorProcessingRecordResource;

class AvgProcessorProcessingRecordChildrenRelationManager extends ChildrenRelationManager
{
    protected static string $resource = AvgProcessorProcessingRecordResource::class;
}
