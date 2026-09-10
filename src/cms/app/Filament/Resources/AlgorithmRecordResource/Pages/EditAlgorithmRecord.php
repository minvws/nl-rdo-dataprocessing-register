<?php

declare(strict_types=1);

namespace App\Filament\Resources\AlgorithmRecordResource\Pages;

use App\Filament\Actions\CloneAction;
use App\Filament\Actions\CreateSnapshotAction;
use App\Filament\Pages\ProcessingRecordEditRecord;
use App\Filament\Resources\AlgorithmRecordResource;
use App\Filament\Resources\Pages\Concerns\HasDraftAutosave;
use Filament\Actions\DeleteAction;

class EditAlgorithmRecord extends ProcessingRecordEditRecord
{
    use HasDraftAutosave;

    protected static string $resource = AlgorithmRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CloneAction::make(),
            CreateSnapshotAction::makeWithChangesCheck($this->data, $this->savedDataHash),
            DeleteAction::make(),
        ];
    }
}
