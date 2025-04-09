<?php

declare(strict_types=1);

namespace App\Filament\Resources\ResponsibleLegalEntityResource\Pages;

use App\Filament\Resources\ResponsibleLegalEntityResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewResponsibleLegalEntity extends ViewRecord
{
    protected static string $resource = ResponsibleLegalEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}
