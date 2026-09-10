<?php

declare(strict_types=1);

namespace App\Filament\Resources\AvgProcessorProcessingRecordResource\Pages;

use App\Filament\Pages\EntityNumberCreateRecord;
use App\Filament\Resources\AvgProcessorProcessingRecordResource;
use App\Filament\Resources\Pages\Concerns\HasDraftAutosave;

class CreateAvgProcessorProcessingRecord extends EntityNumberCreateRecord
{
    use HasDraftAutosave;

    protected static string $resource = AvgProcessorProcessingRecordResource::class;
}
