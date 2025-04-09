<?php

declare(strict_types=1);

namespace App\Filament\Resources\ResponsibleLegalEntityResource\Pages;

use App\Filament\Resources\ResponsibleLegalEntityResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditResponsibleLegalEntity extends EditRecord
{
    protected static string $resource = ResponsibleLegalEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
