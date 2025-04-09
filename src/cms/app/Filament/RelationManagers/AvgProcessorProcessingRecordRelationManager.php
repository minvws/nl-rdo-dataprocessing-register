<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use App\Filament\Resources\AvgProcessorProcessingRecordResource;

class AvgProcessorProcessingRecordRelationManager extends RelationManager
{
    protected static string $languageFile = 'avg_processor_processing_record';
    protected static string $relationship = 'avgProcessorProcessingRecords';
    protected static string $resource = AvgProcessorProcessingRecordResource::class;
}
