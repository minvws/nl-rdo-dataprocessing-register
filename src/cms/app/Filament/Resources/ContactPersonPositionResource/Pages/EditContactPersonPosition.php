<?php

declare(strict_types=1);

namespace App\Filament\Resources\ContactPersonPositionResource\Pages;

use App\Filament\Resources\ContactPersonPositionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContactPersonPosition extends EditRecord
{
    protected static string $resource = ContactPersonPositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
