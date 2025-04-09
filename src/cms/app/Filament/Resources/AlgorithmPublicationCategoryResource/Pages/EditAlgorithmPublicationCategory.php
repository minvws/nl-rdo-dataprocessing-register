<?php

declare(strict_types=1);

namespace App\Filament\Resources\AlgorithmPublicationCategoryResource\Pages;

use App\Filament\Resources\AlgorithmPublicationCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAlgorithmPublicationCategory extends EditRecord
{
    protected static string $resource = AlgorithmPublicationCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
