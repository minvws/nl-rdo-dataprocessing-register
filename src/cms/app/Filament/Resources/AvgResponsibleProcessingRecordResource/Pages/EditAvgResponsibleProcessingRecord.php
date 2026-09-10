<?php

declare(strict_types=1);

namespace App\Filament\Resources\AvgResponsibleProcessingRecordResource\Pages;

use App\Filament\Actions\CloneAction;
use App\Filament\Actions\CreateSnapshotAction;
use App\Filament\Actions\GoToPublicPageAction;
use App\Filament\Pages\ProcessingRecordEditRecord;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource;
use App\Filament\Resources\Pages\Concerns\HasDraftAutosave;
use Filament\Actions\DeleteAction;

class EditAvgResponsibleProcessingRecord extends ProcessingRecordEditRecord
{
    use HasDraftAutosave;

    protected static string $resource = AvgResponsibleProcessingRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            GoToPublicPageAction::make(),
            CloneAction::make(),
            CreateSnapshotAction::makeWithChangesCheck($this->data, $this->savedDataHash),
            DeleteAction::make(),
        ];
    }
}
