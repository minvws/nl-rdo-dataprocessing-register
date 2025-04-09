<?php

declare(strict_types=1);

namespace App\Filament\Resources\AlgorithmMetaSchemaResource\Pages;

use App\Filament\Resources\AlgorithmMetaSchemaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAlgorithmMetaSchema extends EditRecord
{
    protected static string $resource = AlgorithmMetaSchemaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
