<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use App\Filament\Resources\AvgResponsibleProcessingRecordResource;

class AvgResponsibleProcessingRecordRelationManager extends RelationManager
{
    protected static string $languageFile = 'avg_responsible_processing_record';
    protected static string $relationship = 'avgResponsibleProcessingRecords';
    protected static string $resource = AvgResponsibleProcessingRecordResource::class;
}
