<?php

declare(strict_types=1);

namespace App\Filament\Resources\WpgProcessingRecordServiceResource\Pages;

use App\Filament\Resources\WpgProcessingRecordServiceResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWpgProcessingRecordService extends ViewRecord
{
    protected static string $resource = WpgProcessingRecordServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
