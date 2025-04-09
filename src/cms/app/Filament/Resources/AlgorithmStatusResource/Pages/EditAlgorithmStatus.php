<?php

declare(strict_types=1);

namespace App\Filament\Resources\AlgorithmStatusResource\Pages;

use App\Filament\Resources\AlgorithmStatusResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAlgorithmStatus extends EditRecord
{
    protected static string $resource = AlgorithmStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
