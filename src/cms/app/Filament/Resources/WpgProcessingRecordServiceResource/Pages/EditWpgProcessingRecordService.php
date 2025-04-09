<?php

declare(strict_types=1);

namespace App\Filament\Resources\WpgProcessingRecordServiceResource\Pages;

use App\Filament\Actions\DeleteActionWithRelationChecks;
use App\Filament\Resources\WpgProcessingRecordServiceResource;
use Filament\Resources\Pages\EditRecord;

class EditWpgProcessingRecordService extends EditRecord
{
    protected static string $resource = WpgProcessingRecordServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteActionWithRelationChecks::make()
                ->relations([
                    'wpgProcessingRecords',
                ]),
        ];
    }
}
