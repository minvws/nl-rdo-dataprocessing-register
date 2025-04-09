<?php

declare(strict_types=1);

namespace App\Filament\Resources\AlgorithmMetaSchemaResource\Pages;

use App\Filament\Resources\AlgorithmMetaSchemaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAlgorithmMetaSchema extends ViewRecord
{
    protected static string $resource = AlgorithmMetaSchemaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
