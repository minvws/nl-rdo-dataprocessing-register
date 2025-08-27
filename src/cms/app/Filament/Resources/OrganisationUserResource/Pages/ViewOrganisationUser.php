<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrganisationUserResource\Pages;

use App\Filament\Resources\OrganisationUserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOrganisationUser extends ViewRecord
{
    protected static string $resource = OrganisationUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
