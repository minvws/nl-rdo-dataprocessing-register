<?php

declare(strict_types=1);

namespace App\Filament\Resources\AlgorithmPublicationCategoryResource\Pages;

use App\Filament\Resources\AlgorithmPublicationCategoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAlgorithmPublicationCategory extends ViewRecord
{
    protected static string $resource = AlgorithmPublicationCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
