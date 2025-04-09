<?php

declare(strict_types=1);

namespace App\Filament\Resources\ContactPersonPositionResource\Pages;

use App\Filament\Resources\ContactPersonPositionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewContactPersonPosition extends ViewRecord
{
    protected static string $resource = ContactPersonPositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
