<?php

declare(strict_types=1);

namespace App\Filament\Resources\AvgProcessorProcessingRecordServiceResource\Pages;

use App\Filament\Resources\AvgProcessorProcessingRecordServiceResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAvgProcessorProcessingRecordService extends ViewRecord
{
    protected static string $resource = AvgProcessorProcessingRecordServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
