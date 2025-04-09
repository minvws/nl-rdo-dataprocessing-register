<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserOrganisationResource\Pages;

use App\Filament\Resources\UserOrganisationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;

class ViewUserOrganisation extends ViewRecord
{
    protected static string $resource = UserOrganisationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
