<?php

declare(strict_types=1);

namespace App\Filament\Resources\AvgProcessorProcessingRecordResource\Pages;

use App\Filament\Actions\CloneAction;
use App\Filament\Actions\CreateSnapshotAction;
use App\Filament\Pages\ProcessingRecordEditRecord;
use App\Filament\Resources\AvgProcessorProcessingRecordResource;
use App\Filament\Resources\Pages\Concerns\HasDraftAutosave;
use Filament\Actions\DeleteAction;

class EditAvgProcessorProcessingRecord extends ProcessingRecordEditRecord
{
    use HasDraftAutosave;

    protected static string $resource = AvgProcessorProcessingRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CloneAction::make(),
            CreateSnapshotAction::makeWithChangesCheck($this->data, $this->savedDataHash),
            DeleteAction::make(),
        ];
    }
}
