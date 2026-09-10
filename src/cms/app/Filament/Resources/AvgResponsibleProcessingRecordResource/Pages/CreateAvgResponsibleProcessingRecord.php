<?php

declare(strict_types=1);

namespace App\Filament\Resources\AvgResponsibleProcessingRecordResource\Pages;

use App\Filament\Pages\EntityNumberCreateRecord;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource;
use App\Filament\Resources\Pages\Concerns\HasDraftAutosave;

class CreateAvgResponsibleProcessingRecord extends EntityNumberCreateRecord
{
    use HasDraftAutosave;

    protected static string $resource = AvgResponsibleProcessingRecordResource::class;
}
