<?php

declare(strict_types=1);

namespace App\Filament\Resources\AvgProcessorProcessingRecordServiceResource\Pages;

use App\Filament\Actions\DeleteActionWithRelationChecks;
use App\Filament\Resources\AvgProcessorProcessingRecordServiceResource;
use Filament\Resources\Pages\EditRecord;

class EditAvgProcessorProcessingRecordService extends EditRecord
{
    protected static string $resource = AvgProcessorProcessingRecordServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteActionWithRelationChecks::make(),
        ];
    }
}
