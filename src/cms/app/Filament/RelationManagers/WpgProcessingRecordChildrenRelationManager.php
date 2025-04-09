<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use App\Filament\Resources\WpgProcessingRecordResource;

class WpgProcessingRecordChildrenRelationManager extends ChildrenRelationManager
{
    protected static string $resource = WpgProcessingRecordResource::class;
}
