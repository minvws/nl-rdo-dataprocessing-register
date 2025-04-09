<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProcessorResource\Pages;

use App\Filament\Resources\ProcessorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProcessors extends ListRecords
{
    protected static string $resource = ProcessorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
