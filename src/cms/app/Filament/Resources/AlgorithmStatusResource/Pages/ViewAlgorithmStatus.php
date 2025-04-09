<?php

declare(strict_types=1);

namespace App\Filament\Resources\AlgorithmStatusResource\Pages;

use App\Filament\Resources\AlgorithmStatusResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAlgorithmStatus extends ViewRecord
{
    protected static string $resource = AlgorithmStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
