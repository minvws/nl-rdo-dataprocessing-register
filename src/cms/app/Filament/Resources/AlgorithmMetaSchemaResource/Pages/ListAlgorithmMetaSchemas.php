<?php

declare(strict_types=1);

namespace App\Filament\Resources\AlgorithmMetaSchemaResource\Pages;

use App\Filament\Resources\AlgorithmMetaSchemaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAlgorithmMetaSchemas extends ListRecords
{
    protected static string $resource = AlgorithmMetaSchemaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
