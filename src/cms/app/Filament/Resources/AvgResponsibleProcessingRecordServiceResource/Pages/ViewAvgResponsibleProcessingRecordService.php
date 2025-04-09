<?php

declare(strict_types=1);

namespace App\Filament\Resources\AvgResponsibleProcessingRecordServiceResource\Pages;

use App\Filament\Resources\AvgResponsibleProcessingRecordServiceResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAvgResponsibleProcessingRecordService extends ViewRecord
{
    protected static string $resource = AvgResponsibleProcessingRecordServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
