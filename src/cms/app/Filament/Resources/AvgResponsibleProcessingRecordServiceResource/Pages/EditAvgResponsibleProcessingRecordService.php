<?php

declare(strict_types=1);

namespace App\Filament\Resources\AvgResponsibleProcessingRecordServiceResource\Pages;

use App\Filament\Actions\DeleteActionWithRelationChecks;
use App\Filament\Resources\AvgResponsibleProcessingRecordServiceResource;
use Filament\Resources\Pages\EditRecord;

class EditAvgResponsibleProcessingRecordService extends EditRecord
{
    protected static string $resource = AvgResponsibleProcessingRecordServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteActionWithRelationChecks::make(),
        ];
    }
}
